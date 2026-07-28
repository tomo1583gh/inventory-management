<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\StockLog;
use App\Models\Item;
use Illuminate\Http\Request;

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
            ->with('user')
            ->orderByDesc('acted_at')
            ->orderByDesc('id')
            ->paginate(10);
        
            return view('items.logs',compact('item', 'logs'));
    }
}