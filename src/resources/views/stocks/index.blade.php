@extends('layouts.app')

@section('title', '在庫一覧')

@section('content')

<h2>在庫一覧</h2>

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
                現在個数
            
                @if ($sort === 'stock')
                    {{ $direction === 'asc' ? '▲' : '▼' }}
                @endif
            </a>
        </th>
    </tr>

    @forelse($stocks as $stock)

    <tr>
        <td>{{ $stock->category_name ?? '未設定' }}</td>
        <td>{{ $stock->name }}</td>
        <td>{{ $stock->sku }}</td>
        <td>{{ $stock->unit }}</td>
        <td>{{ $stock->current_qty ?? 0 }}</td>
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