@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

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
            <th>現在在庫数</th>
            <td class="text-right">
                @if (floor($currentQty) == $currentQty)
                    {{ number_format($currentQty, 0) }}
                @else
                    {{ number_format($currentQty, 2) }}
                @endif
            </td>
        </tr>

        <tr>
            <th>在庫状況</th>
            <td class="text-center">
                @if ($currentQty <= 0)
                    <span class="stock-status stock-out">
                        在庫切れ
                    </span>
                @elseif (
                    $item->minimum_stock > 0
                    && $currentQty <= $item->minimum_stock
                )
                    <span class="stock-status stock-low">
                        残りわずか
                    </span>
                @else
                    <span class="stock-status stock-normal">
                        在庫あり
                    </span>
                @endif
            </td>
        </tr>

        <tr>
            <th>商品メモ</th>

            <td>
                {!! nl2br(e($item->note ?? '')) !!}
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

    <a href="{{ route('items.logs', $item) }}">
        この商品の入出庫履歴を見る
    </a>


    <p>
        <a href="{{ route('items.edit', $item) }}">編集する</a>
    </p>

    <p>
        <a href="{{route('items.index') }}">商品一覧へ戻る</a>
    </p>

@endsection