@extends('layouts.app')

@section('title', '在庫一覧')

@section('content')

<h2>在庫一覧</h2>

<table border="1" cellpadding="5">

    <tr>
        <th>カテゴリー</th>
        <th>商品名</th>
        <th>管理番号</th>
        <th>現在個数</th>
    </tr>

    @forelse($stocks as $stock)

    <tr>
        <td>{{ $stock->category_name ?? '未設定' }}</td>
        <td>{{ $stock->name }}</td>
        <td>{{ $stock->sku }}</td>
        <td>{{ $stock->current_qty ?? 0 }}</td>
        <td>{{ $stock->unit }}</td>
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