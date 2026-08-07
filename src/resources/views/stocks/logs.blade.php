@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '入出庫履歴')

@section('content')


    <h2>入出庫履歴</h2>

    <form action="{{ route('stocks.logs') }}" method="GET">

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

        <button type="submit">
            検索
        </button>

        <a href="{{ route('stocks.logs') }}">
            リセット
        </a>
    </form>

    <div>
        <p>
            <a href="{{ route('stocks.import.create') }}">
                入出庫CSVインポート
            </a>
        </p>

        <p>
            <a href="{{ route('stocks.logs.export.csv', [
                'item_id' => $itemId,
                'type' => $type,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]) }}">
                入出庫履歴をCSV出力
            </a>
        </p>
    </div>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($logs->isEmpty())
        <p>入出庫履歴はありません。</p>
    @else
        <table border="1">
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
                        <td class="text-center">
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
                        <td class="text-center">
                            @if ($log->corrected_log_id)
                                <strong>訂正理由 : </strong>
                                {!! nl2br(e($log->correction_reason)) !!}
                            @else
                                {!! nl2br(e($log->note ?? '-')) !!}
                            @endif
                        </td>

                        <td class="text-center">
                            @if ($log->corrected_log_id)
                                訂正記録
                            @elseif ($log->correctionLog)
                                訂正済み
                            @else
                                <a href="{{ route('stock-logs.corrections.create', $log) }}">
                                    訂正
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    @endif

    <p>
        <a href="{{ route('stocks.in.create') }}">入庫登録</a>
    </p>

    <p>
        <a href="{{ route('stocks.out.create') }}">出庫登録</a>
    </p>

    <p>
        <a href="{{ route('items.index') }}">商品一覧へ戻る</a>
    </p>

@endsection