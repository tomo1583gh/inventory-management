<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $sku = $request->input('sku');
        $categoryId = $request->input('category_id');

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = [
            'name',
            'category',
            'created_at',
        ];

        $allowedDirections = [
            'asc',
            'desc',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        $items = Item::with('category')
            ->when($q, function ($query, $q) {
                $query->where('items.name', 'like', "%{$q}%");
            })
            ->when($sku,function ($query, $sku) {
                $query->where('items.sku', 'like', "%{$sku}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('items.category_id', $categoryId);
            });

        if ($sort === 'category') {
            $items->leftJoin(
                'categories',
                'items.category_id',
                '=',
                'categories.id'
            )
            ->select('items.*')
            ->orderBy('categories.name', $direction);
        } else {
            $items->orderBy("items.{$sort}", $direction);
        }

        $items = $items
            ->paginate(10)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

            return view('items.index',compact(
                    'items', 
                    'q',
                    'sku',
                    'categoryId',
                    'categories',
                    'sort',
                    'direction'
            ));
    }

    /**
     * 商品登録画面を表示
     */
    public function create()
    {
        $categories = Category::orderBy('id')->get();

        return view('items.create', compact('categories'));
    }

    /**
     * 商品を登録
     */
    public function store(ItemStoreRequest $request)
    {
        Item::create($request->validated());

        return redirect()
            ->route('items.index')
            ->with('success', '商品を登録しました。');
    }

    /**
     * 商品編集画面を表示
     */
    public function edit(Item $item)
    {
        $categories = Category::orderBy('id')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * 商品情報を更新
     */
    public function update(ItemUpdateRequest $request, Item $item)
    {
        $item->update($request->validated());

        return redirect()
            ->route('items.index')
            ->with('success', '商品情報を更新しました。');
    }

    /**
     * 商品を削除
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', '商品を削除しました。');
    }
}