<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\StockLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StockCorrectionRequest;

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

    /**
     * 入出庫履歴
     */
    public function logs()
    {
        $logs = StockLog::with(['item', 'user'])
        ->with([
            'item.category',
            'user',
            'correctedLog',
            'correctionLog',
        ])
        ->orderByDesc('acted_at')
        ->orderByDesc('id')
        ->paginate(20);

        return view('stocks.logs', compact('logs'));
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
}