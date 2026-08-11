@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '在庫一覧')

@section('content')

<h2>在庫一覧</h2>

<div class="list-actions">
    <a
        href="{{ route('stocks.export.csv', [
            'sort' => $sort,
            'direction' => $direction,
            'q' => $q,
            'sku' => $sku,
            'category_id' => $categoryId,
            'status' => $status,
        ]) }}"
        class="btn btn-secondary"
    >
        在庫一覧CSV出力
    </a>
</div>

<h3 class="search-title">
    検索条件
</h3>

<form
    action ="{{ route('stocks.index') }}"
    method="GET"
    class="search-form"
>

    <div>
        <label for="category_id">カテゴリー</label>

        <select
            id="category_id"
            name="category_id"
        >
            <option value="">
                すべて
            </option>

            @foreach($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected($categoryId == $category->id)
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for "q">商品名</label>

        <input
            id="q"
            type="text"
            name="q"
            value="{{ $q }}"
        >
    </div>

    <div>
        <label for="status">在庫状態</label>

        <select
            id="status"
            name="status"
        >
            <option value="">
                すべて
            </option>

            <option
                value="normal"
                @selected($status === 'normal')
            >
                正常
            </option>

            <option
                value="low"
                @selected($status === 'low')
            >
                在庫不足
            </option>

            <option
                value="out"
                @selected($status === 'out')
            >
                在庫切れ
            </option>
        </select>
    </div>

    <div>
        <label for="direction">順序</label>

        <select
            id="direction"
            name="direction"
        >
            <option
                value="asc"
                @selected($direction == 'asc')
            >
                昇順
            </option>

            <option
                value="desc"
                @selected($direction == 'desc')
            >
                降順
            </option>
        </select>
    </div>

    <div class="search-actions">
        <button
            type="submit"
            class="btn btn-primary"
        >
            検索
        </button>

        <a
            href="{{ route('stocks.index') }}"
            class="btn-light"
        >
            リセット
        </a>
    </div>

</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>カテゴリー</th>

                <th>
                    <a href="{{ route('stocks.index', [
                        'sort' => 'name',
                        'direction' =>
                            $sort === 'name' && $direction === 'asc'
                                ? 'desc'
                                : 'asc',
                    ]) }}">
                        商品名

                        @if ($sort === 'name')
                            {{ $direction === 'asc' ? '▲' : '▼' }}
                        @endif
                    </a>
                </th>

                <th>管理番号</th>
                <th>単位</th>

                <th>
                    <a href="{{ route('stocks.index', [
                        'sort' => 'stock',
                        'direction' =>
                            $sort === 'stock' && $direction === 'asc'
                                ? 'desc'
                                : 'asc',
                    ]) }}">
                        現在庫数

                        @if ($sort === 'stock')
                            {{ $direction === 'asc' ? '▲' : '▼' }}
                        @endif
                    </a>
                </th>

                <th>最低在庫数</th>
                <th>在庫状況</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($stocks as $stock)
                <tr>
                    <td class="text-center">
                        {{ $stock->category_name ?? '未設定' }}
                    </td>
                    <td class="text-left">
                        <a href="{{ route('items.show', $stock->id) }}">
                        {{ $stock->name }}
                    </td>
                    <td class="text-center">
                        {{ $stock->sku }}
                    </td>
                    <td class="text-center">
                        {{ $stock->unit }}
                    </td>

                    <td class="text-right">
                        @if (floor($stock->current_qty) == $stock->current_qty)
                            {{ number_format($stock->current_qty, 0) }}
                        @else
                            {{ number_format($stock->current_qty, 2) }}
                        @endif
                    </td>

                    <td class="text-right">
                        {{ $stock->minimum_stock }}
                    </td>

                    <td class="text-center">
                        @if ($stock->current_qty <= 0)
                            <span class="stock-status stock-out">
                                在庫切れ
                            </span>
                        @elseif (
                            $stock->minimum_stock > 0 &&
                            $stock->current_qty <= $stock->minimum_stock
                        )
                            <span class="stock-status stock-low">
                                在庫不足
                            </span>
                        @else
                            <span class="stock-status stock-normal">
                                正常
                            </span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        データがありません。
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $stocks->links() }}

@endsection