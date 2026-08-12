@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品詳細')

@section('content')

    <h2>商品詳細</h2>

    <div class="detail-card">
        <table class="detail-table">
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
                        $item->minimum_stock > 0 &&
                        $currentQty <= $item->minimum_stock
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
                    <div class="memo-box">
                        {!! nl2br(e($item->note ?? '')) !!}
                    </div>
                </td>
            </tr>

            <tr>
                <th>登録日</th>
                <td>
                    {{ $item->created_at->format('Y-m-d') }}
                </td>
            </tr>

            <tr>
                <th>更新日</th>
                <td>
                    {{ $item->updated_at->format('Y-m-d') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="detail-actions">
        <a 
            href="{{ route('items.edit', $item) }}"
            class="btn btn-primary"
            >
                編集する
        </a>

        <a 
            href="{{ route('items.logs', $item) }}"
            class="btn btn-secondary"
        >
            この商品の入出庫履歴を見る
        </a>

        <a 
            href="{{ route('items.index') }}"
            class="btn-light"
            >
                商品一覧へ戻る
        </a>
    </div>

@endsection