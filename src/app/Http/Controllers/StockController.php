<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\StockLog;
use App\Models\Item;

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
    public function index()
    {
        //
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
}