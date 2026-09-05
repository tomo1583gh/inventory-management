@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'ダッシュボード')

@section('content')

<h2>ダッシュボード</h2>

<section class="dashboard-section">
    <h3>在庫状況</h3>

    <div class="dashboard-summary">

        <div class="summary-item">
            <span class="summary-label">
                登録商品数
            </span>

            <div class="summary-value">
                <strong>{{ $itemCount }}</strong>
                <span>件</span>
            </div>
        </div>

        <div class="summary-item summary-danger">
            <span class="summary-label">
                在庫切れ商品数
            </span>

            <div class="summary-value">
                <strong>{{ $outOfStockCount }}</strong>
                <span>件</span>
            </div>
        </div>

        <div class="summary-item summary-warning">
            <span class="summary-label">
                在庫不足商品数
            </span>

            <div class="summary-value">
                <strong>{{ $lowStockCount }}</strong>
                <span>件</span>
            </div>
        </div>

    </div>
</section>

<section class="dashboard-section">
    <h3>在庫切れ商品</h3>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>カテゴリー</th>
                    <th>商品名</th>
                    <th>管理番号</th>
                    <th>現在庫</th>
                    <th>単位</th>
                    <th>最低在庫数</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($outOfStockItems as $item)
                    <tr class="stock-danger-row">
                        <td class="text-center">
                            {{ $item->category->name ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('items.show', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>

                        <td class="text-center">
                            {{ $item->sku }}
                        </td>

                        <td class="text-right">
                            @if (floor($item->current_qty) == $item->current_qty)
                                {{ number_format($item->current_qty, 0) }}
                            @else
                                {{ number_format($item->current_qty, 2) }}
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $item->unit }}
                        </td>

                        <td class="text-right">
                            @if (floor($item->minimum_stock) == $item->minimum_stock)
                                {{ number_format($item->minimum_stock, 0) }}
                            @else
                                {{ number_format($item->minimum_stock, 2) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            在庫切れの商品はありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="dashboard-section">
    <h3>在庫不足商品</h3>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>カテゴリー</th>
                    <th>商品名</th>
                    <th>管理番号</th>
                    <th>現在庫</th>
                    <th>単位</th>
                    <th>最低在庫数</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($lowStockItems as $item)
                    <tr class="stock-warning-row">
                        <td class="text-center">
                            {{ $item->category->name ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('items.show', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>

                        <td class="text-center">
                            {{ $item->sku }}
                        </td>

                        <td class="text-right">
                            @if (floor($item->current_qty) == $item->current_qty)
                                {{ number_format($item->current_qty, 0) }}
                            @else
                                {{ number_format($item->current_qty, 2) }}
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $item->unit }}
                        </td>

                        <td class="text-right">
                            @if (floor($item->minimum_stock) == $item->minimum_stock)
                                {{ number_format($item->minimum_stock, 0) }}
                            @else
                                {{ number_format($item->minimum_stock, 2) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            在庫不足の商品はありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="dashboard-section">
    <h3>最近登録された商品</h3>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>登録日時</th>
                    <th>カテゴリー</th>
                    <th>商品名</th>
                    <th>管理番号</th>
                    <th>単位</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($recentItems as $item)
                    <tr>
                        <td class="text-center">
                            {{ $item->created_at->format('Y/m/d H:i') }}
                        </td>

                        <td class="text-center">
                            {{ $item->category->name ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('items.show',$item) }}">
                                {{ $item->name }}
                            </a>
                        </td>

                        <td class="text-center">
                            {{ $item->sku }}
                        </td>

                        <td class="text-center">
                            {{ $item->unit }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            登録された商品はありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="dashboard-section">
    <h3>最近の入出庫履歴</h3>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>商品</th>
                    <th>区分</th>
                    <th>数量</th>
                    <th>単位</th>
                    <th>担当者</th>
                    <th>入出庫メモ</th>
                    <th>状態</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($recentLogs as $log)
                    <tr>
                        <td class="text-center">
                            {{ $log->acted_at?->format('Y/m/d H:i') ?? '日時不明' }}
                        </td>

                        <td>
                            @if ($log->item)
                            <a href="{{ route('items.show', $log->item) }}">
                                {{ $log->item->name }}
                            </a>
                            @else
                                削除済み商品
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $log->type === 'in' ? '入庫' : '出庫' }}
                        </td>

                        <td class="text-right">
                            @if (floor($log->qty) == $log->qty)
                                {{ number_format($log->qty, 0) }}
                            @else
                                {{ number_format($log->qty, 2) }}
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $log->item?->unit ?? '-' }}
                        </td>

                        <td>
                            {{ $log->user?->name ?? '不明' }}
                        </td>

                        <td>
                            @if ($log->corrected_log_id !== null)
                                <strong>訂正理由 : </strong>
                                {!! nl2br(e($log->correction_reason ?? '')) !!}
                            @else
                                {!! nl2br(e($log->note ?? '')) !!}
                            @endif
                        </td>

                        <td class="text-center">
                            @if ($log->corrected_log_id !== null)
                                訂正記録
                            @elseif ($log->correctionLog !== null)
                                訂正済み
                            @else
                                通常
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            入出庫履歴はありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="dashboard-actions">
        <a
            href="{{ route('stocks.logs') }}"
            class="btn-light"
        >
            入出庫履歴をすべて見る
        </a>
    </div>
</section>

@endsection
