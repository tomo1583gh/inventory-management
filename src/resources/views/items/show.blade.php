@extends('layouts.app')

@section('title', '商品詳細')

@section('content')

    <h2>商品詳細</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>カテゴリー</th>
            <td class="text-center">
              {{ $item->category->name ?? '未設定' }}
            </td>
        </tr>

        <tr>
            <th>商品名</th>
            <td class="text-center">
              {{ $item->name }}
            </td>
        </tr>

        <tr>
            <th>管理番号</th>
            <td class="text-center">
              {{ $item->sku }}
            </td>
        </tr>

        <tr>
            <th>単位</th>
            <td class="text-center">
              {{ $item->unit }}
            </td>
        </tr>

        <tr>
            <th>最低在庫数</th>
            <td class="text-right">
                {{ $item->minimum_stock }}
            </td>
        </tr>

        <tr>
            <th>登録日</th>
            <td class="text-center">
              {{ $item->created_at->format('Y-m-d') }}
            </td>
        </tr>

        <tr>
            <th>更新日</th>
            <td class="text-center">
              {{ $item->updated_at->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <p>
        <a href="{{ route('items.edit', $item) }}">編集する</a>
    </p>

    <p>
        <a href="{{route('items.index') }}">商品一覧へ戻る</a>
    </p>

@endsection
          