@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')

<h2>ダッシュボード</h2>

<div>
    <h3>在庫状況</h3>

    <p>
        登録商品数 :
        <strong>{{ $itemCount }}</strong> 件
    </p>

    <p>
        在庫切れ商品数 :
        <strong>{{ $outOfStockCount }}</strong> 件
    </p>

    <p>
        在庫不足商品数 :
        <strong>{{ $lowStockCount }}</strong> 件
    </p>
</div>

<hr>

<div>
    <h3>最近の入出庫履歴</h3>

    <table border="1" cellpadding="5">
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

    <p>
        <a href="{{ route('stocks.logs') }}">
            入出庫履歴をすべて見る
        </a>
    </p>
</div>

@endsection
