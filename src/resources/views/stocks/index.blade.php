@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '在庫一覧')

@section('content')

<h2>在庫一覧</h2>

<p>
    <a href="{{ route('stocks.export.csv', [
    'sort' => $sort,
    'direction' => $direction,
    ]) }}">
        在庫一覧をCSV出力
    </a>
<p>

<table border="1" cellpadding="5">

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
            <a href="{{ route('stocks.index',[
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
    </tr>

    @forelse($stocks as $stock)

    <tr>
        <td class="text-center">
            {{ $stock->category_name ?? '未設定' }}
        </td>
        <td class="text-center">
            {{ $stock->name }}
        </td>
        <td class="text-center">
            {{ $stock->sku }}
        </td>
        <td class="text-center">
            {{ $stock->unit }}
        </td>
        <td class="text-right">

            <span
                class="
                    @if ($stock->current_qty <= 0)
                        stock-out
                    @elseif (
                        $stock->minimum_stock > 0 &&
                        $stock->current_qty <= $stock->minimum_stock
                    )
                        stock-low
                    @endif
                "
            >
                @if (floor($stock->current_qty) == $stock->current_qty)
                    {{ number_format($stock->current_qty, 0) }}
                @else
                    {{ number_format($stock->current_qty, 2) }}
                @endif
            </span>
        </td>
        <td class="text-right">
            {{ $stock->minimum_stock }}
        </td>
    </tr>

    @empty

    <tr>
        <td colspan="5">
            データがありません。
        </td>
    </tr>

    @endforelse

</table>

{{ $stocks->links() }}

@endsection