@extends('layouts.app')

@section('title', '入出庫履歴')

@section('content')


    <h2>入出庫履歴</h2>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($logs->isEmpty())
        <p>入出庫履歴はありません。</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>作業日</th>
                    <th>商品</th>
                    <th>区分</th>
                    <th>数量</th>
                    <th>単位</th>
                    <th>担当者</th>
                    <th>メモ</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->acted_at->format('Y-m-d') }}</td>
                        <td>{{ $log->item->name }}</td>
                        <td>{{ $log->type === 'in' ? '入庫' : '出庫' }}</td>
                        <td class="text-right">
                            @if (floor($log->qty) == $log->qty)
                                {{number_format($log->qty, 0) }}
                            @else
                                {{ number_format($log->qty, 2) }}
                            @endif
                        </td>
                        <td>{{ $log->item->unit }}</td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->note ?? '-' }}</td>
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