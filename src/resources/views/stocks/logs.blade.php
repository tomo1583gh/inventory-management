@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '入出庫履歴')

@section('content')


    <h2>入出庫履歴</h2>

    <h3 class="search-title">
        検索条件
    </h3>

    <form 
        action="{{ route('stocks.logs') }}" 
        method="GET"
        class="search-form"
    >

        <div>
            <label for="item_id">商品</label>

            <select id="item_id" name="item_id">
                <option value="">すべて</option>

                @foreach ($items as $item)
                    <option
                        value="{{ $item->id }}"
                        @selected($itemId == $item->id)
                    >
                        {{ $item->name }} ({{ $item->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="type">区分</label>

            <select id="type" name="type">
                <option value="">すべて</option>
                <option value="in" @selected($type === 'in')>
                    入庫
                </option>
                <option value="out" @selected($type === 'out')>
                    出庫
                </option>
            </select>
        </div>

        <div>
            <label for="date_from">開始日</label>

            <input
                type="date"
                id="date_from"
                name="date_from"
                value="{{ $dateFrom }}"
            >
        </div>

        <div>
            <label for="date_to">終了日</label>

            <input
                type="date"
                id="date_to"
                name="date_to"
                value="{{ $dateTo }}"
            >
        </div>

        <div class="search-actions">
            <button 
                type="submit"
                class="btn btn-primary"
            >
                検索
            </button>

            <a href="{{ route('stocks.logs') }}"
            class="btn-light"
        >
            リセット
            </a>
        </div>
    </form>

    <div class="list-actions log-actions">
        <a 
            href="{{ route('stocks.import.create') }}"
            class="btn btn-secondary"
        >
            入出庫CSVインポート
        </a>

        <a 
            href="{{ route('stocks.logs.export.csv', [
                'item_id' => $itemId,
                'type' => $type,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]) }}"
            class="btn btn-secondary"
        >
            入出庫履歴CSV出力
        </a>
    </div>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($logs->isEmpty())
        <p>入出庫履歴はありません。</p>
    @else
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
                        <th>操作</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->acted_at->format('Y-m-d') }}</td>
                            <td class="text-left">
                                {{ $log->item->name }}
                            </td>
                            <td>{{ $log->type === 'in' ? '入庫' : '出庫' }}</td>
                            <td class="text-right">
                                @if (floor($log->qty) == $log->qty)
                                    {{number_format($log->qty, 0) }}
                                @else
                                    {{ number_format($log->qty, 2) }}
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $log->item->unit }}
                            </td>
                            <td class="text-center">
                                {{ $log->user->name }}
                            </td>
                            <td class="text-left">
                                @if ($log->corrected_log_id)
                                    <strong>訂正理由 : </strong>
                                    {!! nl2br(e($log->correction_reason)) !!}
                                @else
                                    {!! nl2br(e($log->note ?? '-')) !!}
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($log->corrected_log_id)
                                    <span class="log-status log-correction">
                                        訂正記録
                                    </span>

                                @elseif ($log->correctionLog)
                                    <span class="log-status log-correction">
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
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    @endif

        <div class="page-actions">
            <a 
                href="{{ route('stocks.in.create') }}"
                class="btn btn-primary"
            >
                入庫登録
            </a>

            <a 
                href="{{ route('stocks.out.create') }}"
                class="btn btn-primary"
            >
                出庫登録
            </a>

            <a 
                href="{{ route('items.index') }}"
                class="btn-light"
            >
                商品一覧へ戻る
            </a>
        </div>

@endsection