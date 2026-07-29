<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockLog;

class DashboardController extends Controller
{
    public function index()
    {
        $items = Item::query()
            ->with('category')
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
                'items.category_id'
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
                ) AS current_qty
            ")
            ->groupBy(
                'items.id',
                'items.name',
                'items.sku',
                'items.unit',
                'items.minimum_stock',
                'items.category_id'
            )
            ->get();

        /*
        * 登録商品数
        */
        $itemCount = $items->count();

        /*
        * 在庫切れ商品
        * 現在在庫数が0以下の商品
        */
        $outOfStockItems = $items
            ->filter(function ($item) {
                return $item->current_qty <= 0;
            })
            ->values();
        
            /*
            * 在庫不足商品
            * 在庫は残っているが、最低在庫数以下の商品
            */
            $lowStockItems = $items
                ->filter(function ($item) {
                    return $item->current_qty > 0
                    && $item->minimum_stock > 0
                    && $item->current_qty <= $item->minimum_stock;
                })
                ->values();

            /*
            * 件数
            */
                $outOfStockCount = $outOfStockItems->count();
                $lowStockCount = $lowStockItems->count();

            $recentLogs = StockLog::query()
                ->with([
                    'item',
                    'user',
                    'correctedLog',
                    'correctionLog',
                ])
                ->orderByDesc('acted_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get();

            $recentItems = Item::query()
                ->with('category')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // ここでビューへ戻す

            return view('dashboard', compact(
                'itemCount', 
                'outOfStockCount',
                'lowStockCount',
                'outOfStockItems',
                'lowStockItems',
                'recentItems',
                'recentLogs'
                )
            );
    }
}
