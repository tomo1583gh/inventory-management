<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\StockLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StockCorrectionRequest;
use App\Http\Requests\StockLogImportRequest;
use Carbon\Carbon;

class StockController extends Controller
{
    /**
     *  入庫登録画面
     */
    public function createIn()
    {
        $items = Item::orderBy('name')->get();

        return view('stocks.in', compact('items'));
    }

    /**
     * 入庫登録
     */
    public function storeIn(StockInRequest $request)
    {
        StockLog::create([
            ...$request->validated(),
            'type' => 'in',
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('stocks.logs')
            ->with('success', '入庫を登録しました');
    }

    /**
     * 出庫登録画面
     */
    public function createOut()
    {
        $items = Item::orderBy('name')->get();

        return view('stocks.out', compact('items'));
    }

    /**
     * 出庫登録
     */
    public function storeOut(StockOutRequest $request)
    {
        StockLog::create([
            ...$request->validated(),
            'type' => 'out',
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('stocks.logs')
            ->with('success', '出庫を登録しました。');
    }

    /**
     * 在庫一覧
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction','asc');

        $allowedSorts = [
            'name',
            'stock',
        ];

        $allowedDirections = [
            'asc',
            'desc',
        ];

        /*
        * 許可していない並び替え項目が指定された場合は商品名順に戻す
        */
        if (!in_array($sort, $allowedSorts,true)) {
            $sort = 'name';
        }

        /*
        * asc , desc以外が指定された場合は昇順に戻す。
        */
        if (!in_array($direction, $allowedDirections,true)) {
            $direction = 'asc';
        }

        $stocks = Item::query()
            ->leftJoin(
                'categories',
                'items.category_id',
                '=',
                'categories.id'
            )
            ->leftJoin(
                'stock_logs',
                'items.id',
                '=',
                'stock_logs.item_id'
            )
            ->select(
                'items.id',
                'items.name',
                'items.sku',
                'items.unit',
                'items.minimum_stock',
                'categories.name as category_name'
            )
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN stock_logs.type = 'in'
                            THEN stock_logs.qty
                            
                            WHEN stock_logs.type = 'out'
                            THEN -stock_logs.qty
                            
                            ELSE 0
                        END
                    ),
                    0
                ) as current_qty
            ")
            ->groupBy(
                'items.id',
                'items.name',
                'items.sku',
                'items.unit',
                'items.minimum_stock',
                'categories.name'
            )
            ->when(
                $sort === 'stock',
                function ($query) use ($direction) {
                    $query->orderBy('current_qty', $direction);
                },
                function ($query) use ($direction) {
                    $query->orderBy('items.name', $direction);
                }
            )
            ->paginate(10)
            ->withQueryString();

        return view(
            'stocks.index', 
            compact(
                'stocks',
                'sort',
                'direction'
            )
        );
    }

    /*
    * 在庫一覧csv出力
    */
    public function exportCsv(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        $allowedSorts = [
            'name',
            'stock',
        ];

        $allowedDirections = [
            'asc',
            'desc',
        ];

        /*
        * 許可されていない並び替え項目の場合は商品名順に戻す
        */
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'name';
        }

        /*
        * asc,desc以外の場合は昇順に戻す
        */
        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'asc';
        }

        $stocks = Item::query()
            ->leftJoin(
                'categories',
                'items.category_id',
                '=',
                'categories.id'
            )
            ->leftJoin(
                'stock_logs',
                'items.id',
                '=',
                'stock_logs.item_id'
            )
            ->select(
                'items.id',
                'items.name',
                'items.sku',
                'items.unit',
                'items.minimum_stock',
                'categories.name as category_name'
            )
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN stock_logs.type = 'in'
                            THEN stock_logs.qty
                            
                            WHEN stock_logs.type = 'out'
                            THEN -stock_logs.qty
                            
                            ELSE 0
                        END
                    ),
                    0
                ) as current_qty
            ")
            ->groupBy(
                'items.id',
                'items.name',
                'items.sku',
                'items.unit',
                'items.minimum_stock',
                'categories.name'
            )
            ->when(
                $sort === 'stock',
                function ($query) use ($direction) {
                    $query->orderBy('current_qty', $direction);
                },
                function ($query) use ($direction) {
                    $query->orderBy('items.name', $direction);
                }
            )
            ->get();

        $fileName = 'stocks_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            function () use ($stocks) {
                $handle = fopen('php://output', 'w');

                /*
                * Excelで日本語が文字化けしないようにBOMを付ける
                */
                fwrite($handle, "\xEF\xBB\xBF");

                /*
                * CSVの見出し
                */
                fputcsv($handle, [
                    'カテゴリー',
                    '商品名',
                    '管理番号',
                    '現在庫',
                    '単位',
                    '最低在庫',
                    '在庫状況',
                ]);

                foreach ($stocks as $stock) {
                    if ($stock->current_qty <= 0) {
                        $status = '在庫切れ';
                    } elseif (
                        $stock->minimum_stock > 0
                        && $stock-> current_qty <= $stock->minimum_stock
                    ) {
                        $status = '在庫不足';
                    } else {
                        $status = '在庫あり';
                    }

                    fputcsv($handle, [
                        $stock->category_name ?? '未設定',
                        $stock->name,
                        $stock->sku,
                        $stock->current_qty,
                        $stock->unit,
                        $stock->minimum_stock,
                        $status,
                    ]);
                }

                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * 入出庫履歴
     */
    public function logs(Request $request)
    {
        $itemId = $request->input('item_id');
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        /*
        * 不正な区分が指定された場合は」、区分なしへ戻す
        */
        if (!in_array($type, ['in', 'out'], true)) {
            $type = null;
        }

        $logs = StockLog::with([
            'item.category',
            'user',
            'correctedLog',
            'correctionLog',
        ])
        ->when($itemId, function ($query,$itemId) {
            $query->where('item_id', $itemId);
        })
        ->when($type, function ($query, $type) {
            $query->where('type', $type);
        })
        ->when($dateFrom, function ($query,$dateFrom) {
            $query->where('acted_at', '>=', $dateFrom);
        })
        ->when($dateTo, function ($query, $dateTo) {
            $query->where('acted_at', '<=', $dateTo);
        })
        ->orderByDesc('acted_at')
        ->orderByDesc('id')
        ->paginate(20)
        ->withQueryString();

    $items = Item::orderBy('name')->get();

        return view('stocks.logs', compact(
            'logs',
            'items',
            'itemId',
            'type',
            'dateFrom',
            'dateTo'
        ));
    }

    /*
    * 入出庫履歴CSV出力
    */
    public function exportLogsCsv(Request $request)
    {
        $itemId = $request->input('item_id');
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if (!in_array($type, ['in', 'out'], true)) {
            $type = null;
        }
        $logs = StockLog::with([
            'item.category',
            'user',
            'correctedLog',
            'correctionLog',
        ])
            ->when($itemId, function ($query, $itemId) {
                $query->where('item_id', $itemId);
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('acted_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('acted_at', '<=', $dateTo);
            })
            ->orderByDesc('acted_at')
            ->orderByDesc('id')
            ->get();

        $fileName = 'stock_logs_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            function () use ($logs) {
                $handle = fopen('php://output', 'w');

                // Excelで日本語が文字化けしにくいようにBOMを付ける
                fwrite($handle, "\xEF\xBB\xBF");

                fputcsv($handle,[
                    '日時',
                    'カテゴリー',
                    '商品名',
                    '管理番号',
                    '区分',
                    '数量',
                    '単位',
                    '担当者',
                    'メモ・訂正理由',
                    '状態',
                ]);

                foreach ($logs as $log) {
                    if ($log->corrected_log_id !== null) {
                        $status = '訂正記録';
                    } elseif ($log->correctionLog !== null) {
                        $status = '訂正済み';
                    } else {
                        $status = '通常';
                    }

                    if ($log->corrected_log_id !== null) {
                        $memo = '訂正理由:'
                            . ($log->correction_reason ?: '理由の記録なし');
                    } else {
                        $memo = $log->note ?? '';
                    }

                    fputcsv($handle, [
                        $log->acted_at->format('Y/m/d H:i'),
                        $log->item?->category?->name ?? '未設定',
                        $log->item?->name ?? '商品情報なし',
                        $log->item?->sku ?? '',
                        $log->type === 'in' ? '入庫' : '出庫',
                        $log->qty,
                        $log->item?->unit ?? '',
                        $log->user?->name ?? '不明',
                        $memo,
                        $status,
                    ]);
                }

                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /*
    * 商品別入出庫履歴
    */
    public function itemLogs(Item $item)
    {
        $item->load('category');

        $logs = $item->stockLogs()
            ->with([
                'user',
                'correctedLog',
                'correctionLog',
            ])
            ->orderByDesc('acted_at')
            ->orderByDesc('id')
            ->paginate(10);

            return view(
                'items.logs',
                compact('item', 'logs')
            );
    }

    public function createCorrection(StockLog $stockLog)
    {
        $stockLog->load([
            'item',
            'user',
            'correctionLog',
        ]);

        if ($stockLog->corrected_log_id !== null) {
            return redirect()
                ->route('items.logs', $stockLog->item_id)
                ->with('error', '訂正記録をさらに訂正することはできません。');
        }

        if ($stockLog->correctionLog !== null) {
            return redirect()
                ->route('items.logs', $stockLog->item_id)
                ->with('error', 'この履歴はすでに訂正されています。');
        }

        return view(
            'stock_logs.correction',
            compact('stockLog')
        );
    }

    public function storeCorrection(
        StockCorrectionRequest $request,
        StockLog $stockLog
    ) {
        $validated = $request->validated();

        DB::transaction(function () use (
            $stockLog,
            $validated
        ) {
            $originalLog = StockLog::query()
                ->with('correctionLog')
                ->lockForUpdate()
                ->findOrFail($stockLog->id);

            if ($originalLog->corrected_log_id !== null) {
                abort(422, '訂正記録をさらに訂正することはできません。');
            }

            if ($originalLog->correctionLog !== null) {
                abort(422, 'この履歴はすでに訂正されています。');
            }

            $correctionType =
                $originalLog->type === 'in'
                ? 'out'
                : 'in';

            StockLog::create([
                'corrected_log_id' => $originalLog->id,
                'item_id' => $originalLog->item_id,
                'user_id' => auth()->id(),
                'type' => $correctionType,
                'qty' => $originalLog->qty,
                'note' => null,
                'correction_reason' =>
                    $validated['correction_reason'],
                'acted_at' => now(),
            ]);
        });

        return redirect()
        ->route('items.logs', $stockLog->item_id)
        ->with('success', '入出庫履歴を訂正しました。');
    }

    /*
    * 入出庫CSVインポート画面
    */
    public function createImport()
    {
        return view('stocks.import');
    }

    /*
    * 入出庫CSVインポート用テンプレート
    */
    public function downloadImportTemplate()
    {
        $fileName = 'stock_log_import_template.csv';

        return response()->streamDownload(
            function () {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\XBF");

                fputcsv($handle, [
                    '管理番号',
                    '区分',
                    '数量',
                    '作業日時',
                    '入出庫メモ',
                ]);

                fputcsv($handle, [
                    'FER-001',
                    '入庫',
                    '2',
                    '2026-08-02 09:00',
                    '仕入れ',
                ]);

                fputcsv($handle, [
                    'FER-001',
                    '出庫',
                    '2',
                    '2026-08-02 14:30',
                    '畑Aで使用',
                ]);

                fclose($handle);
            },
            $fileName,
            [
                'Content-type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /*
    * 入出庫インポート
    */
    public function importCsv(StockLogImportRequest $request)
    {
        $file = $request->file('csv_file');

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return back()
                ->withErrors([
                    'csv_file' => 'CSVファイルを読み込めませんでした。',
                ]);
        }

        // 1行目をヘッダーとして取得
        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return back()
                ->withErrors([
                    'csv_file' => 'CSVファイルにヘッダー行がありません。',
                ]);
        }

        // UTF-8 BOMを除去
        $header[0] = preg_replace(
            '/^\xEF\xBB\xBF/',
            '',
            $header[0]
        );

        $header = array_map('trim', $header);

        $expectedHeader = [
            '管理番号',
            '区分',
            '数量',
            '作業日時',
            '入出庫メモ',
        ];

        if ($header !== $expectedHeader) {
            fclose($handle);

            return back()
                ->withErrors([
                    'csv_file' =>
                        'CSVのヘッダーまたは列の順番が正しくありません。',
                ]);
        }

        $rows = [];
        $lineNumber = 1;

        // 2行目以降を読み込む
        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            $isEmptyRow = count(array_filter(
                $row,
                fn ($value) => trim((string) $value) !== ''
            )) === 0;

            if ($isEmptyRow) {
                continue;
            }

            $rows[] = [
                'line_number' => $lineNumber,
                'data' => $row,
            ];
        }

        fclose($handle);

        try {
            DB::transaction(function () use ($rows, $request) {
                foreach ($rows as $rowData) {
                    $lineNumber = $rowData['line_number'];
                    $row = $rowData['data'];

                    /*
                    * 数列チェック
                    */
                    if (count($row) !== 5) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目の列数が正しくありません。5列で入力してください。"
                        );
                    }

                    [
                        $sku,
                        $type,
                        $qty,
                        $actedAt,
                        $note,
                    ] = $row;

                    $sku = trim($sku);
                    $type = trim($type);
                    $qty = trim($qty);
                    $actedAt = trim($actedAt);
                    $note = trim($note);

                    /*
                    * 必須項目チェック
                    */
                    if ($sku === '') {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：管理番号を入力してください。"
                        );
                    }

                    if ($type === '') {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：区分を入力してください。"
                        );
                    }

                    if ($qty === '') {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：数量を入力してください。"
                        );
                    }

                    if ($actedAt === '') {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：作業日時を入力してください。"
                        );
                    }

                    /*
                    * 商品在庫チェック
                    */
                    $item = Item::where('sku', $sku)->first();

                    if ($item === null) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：管理番号「{$sku}」の商品は登録されていません。"
                        );
                    }

                    /*
                    * 区分チェック
                    */
                    if (!in_array($type, ['入庫', '出庫'], true)) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：区分は「入庫」または「出庫」で入力してください。"
                        );
                    }

                    /*
                    * 数量チェック
                    */
                    if (!is_numeric($qty)) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：数量は数値で入力してください。"
                        );
                    }
                    if ((float) $qty <= 0) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：数量は0より大きい値を入力してください。"
                        );
                    }

                    /*
                    * 作業日時チェック
                    */
                    try {
                        $parsedActedAt = Carbon::createFromFormat(
                            'Y-m-d H:i',
                            $actedAt
                        );

                        if (
                            $parsedActedAt->format('Y-m-d H:i')
                            !== $actedAt
                        ) {
                            throw new \RuntimeException();
                        }
                    } catch (\Throwable $e) {
                        throw new \RuntimeException(
                            "{$lineNumber}行目：作業日時は「YYYY-MM-DD HH:MM」形式で入力してください。"
                        );
                    }

                    StockLog::create([
                        'item_id' => $item->id,
                        'user_id' => $request->user()->id,
                        'type' => $type === '入庫' ? 'in' : 'out',
                        'qty' => $qty,
                        'acted_at' => $parsedActedAt,
                        'note' => $note !== '' ? $note : null,
                    ]);
                }
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withErrors([
                    'csv_file' => $e->getMessage(),
                ]);
        }
        return redirect()
            ->route('stocks.logs')
            ->with('success', '入出庫CSVをインポートしました。');
    }
}