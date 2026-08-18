@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品別入出庫履歴')

@section('content')

<h2>商品別入出庫履歴</h2>

<div class="detail-card">
    <table class="detail-table">
        <tr>
            <th>商品名</th>
            <td>{{ $item->name }}</td>
        </tr>

        <tr>
            <th>カテゴリー</th>
            <td>{{ $item->category->name ?? '未設定' }}</td>
        </tr>

        <tr>
            <th>管理番号</th>
            <td>{{ $item->sku }}</td>
        </tr>
    </table>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>日時</th>
                <th>区分</th>
                <th>数量</th>
                <th>担当者</th>
                <th>入出庫メモ</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>
                        {{ $log->acted_at->format('Y/m/d H:i') }}
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
                        {{ $log->user->name ?? '不明' }}
                    </td>

                    <td class="text-left">
                        @if ($log->corrected_log_id !== null)
                            <strong>訂正理由：</strong>
                            {!! nl2br(e($log->correction_reason ?? '')) !!}
                        @else
                            {!! nl2br(e($log->note ?? '-')) !!}
                        @endif
                    </td>

                    <td class="text-center">
                        @if ($log->corrected_log_id !== null)
                            <span class="log-status log-correction">
                                訂正記録
                            </span>

                        @elseif ($log->correctionLog !== null)
                            <span class="log-status log-corrected">
                                訂正済み
                            </span>

                        @else
                            <a
                                href="{{ route('stock-logs.corrections.create', $log) }}"
                                class="btn btn-light btn-sm"
                            >
                                訂正
                            </a>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        入出庫履歴はありません。
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $logs->links() }}

<div class="page-actions">
    <a
        href="{{ route('items.show', $item) }}"
        class="btn-light"
    >
        商品詳細へ戻る
    </a>
</div>

@endsection