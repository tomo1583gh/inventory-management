@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '入出庫履歴の訂正')

@section('content')

<h2>入出庫履歴の訂正</h2>

<p class="help-text">
    元の履歴は削除・変更せず、反対区分の履歴を追加して取り消します。
</p>
    <div class="detail-card">
        <table class="detail-table">
            <tr>
                <th>商品名</th>
                <td>
                    {{ $stockLog->item->name }}
                </td>
            </tr>

            <tr>
                <th>登録日時</th>
                <td>
                    {{ $stockLog->acted_at->format('Y/m/d H:i') }}
                </td>
            </tr>

            <tr>
                <th>区分</th>
                <td>
                    {{ $stockLog->type === 'in' ? '入庫' : '出庫' }}
                </td>
            </tr>

            <tr>
                <th>数量</th>
                <td class="text-right">
                    @if (floor($stockLog->qty) == $stockLog->qty)
                        {{ number_format($stockLog->qty, 0) }}
                    @else
                        {{ number_format($stockLog->qty, 2) }}
                    @endif

                    {{ $stockLog->item->unit }}
                </td>
            </tr>

            <tr>
                <th>担当者</th>
                <td>
                    {{ $stockLog->user->name ?? '不明' }}
                </td>
            </tr>

            <tr>
                <th>入出庫メモ</th>
                <td>
                    {!! nl2br(e($stockLog->note ?? '-')) !!}
                </td>
            </tr>
        </table>
    </div>

    <h3 class="form-title">
        訂正内容
    </h3>

    <form
        action="{{ route(
            'stock-logs.corrections.store',
            $stockLog
        ) }}"
        method="POST"
        class="form-card correction-form"
    >
        @csrf

        <div class="form-group">
            <label for="correction_reason">
                訂正理由
            </label>

            <textarea
                id="correction_reason"
                name="correction_reason"
                rows="4"
            >{{ old('correction_reason') }}</textarea>

            @error('correction_reason')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="form-actions">
            <button
                type="submit"
                class="btn btn-danger"
                onclick="return confirm('この入出庫履歴を訂正しますか？')"
            >
                この履歴を訂正する
            </button>

            <a
                href="{{ route('items.logs', $stockLog->item_id) }}"
                class="btn-light"
            >
                商品別入出庫履歴へ戻る
            </a>
        </div>
    </form>

@endsection
