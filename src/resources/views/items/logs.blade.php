@extends('layouts.app')

@section('title', '商品別入出庫履歴')

@section('content')

<h2>商品別入出庫履歴</h2>

<table>
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

<br>

<table border="1" cellpadding="5">

    <thead>
        <tr>
            <th>日時</th>
            <th>カテゴリー</th>
            <th>数量</th>
            <th>担当者</th>
            <th>商品メモ</th>
        </tr>
    </thead>

    <tbody>

        @forelse ($logs as $log)

        <tr>

            <td class="text-center">
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

            <td>
                {{ $log->user->name ?? '不明' }}
            </td>

            <td>
                {{ $log->note }}
            </td>

        </tr>

        @empty
      
        <tr>
            <td colspan="5" class="text=center">
                入出庫履歴はありません。
            </td>
        </tr>
        
        @endforelse

    </tbody>

</table>

{{ $logs->links() }}

<br>

<a href="{{ route('items.show', $item) }}">
    商品詳細へ戻る
</a>

@endsection