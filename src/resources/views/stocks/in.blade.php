@extends('layouts.app')

@section('title', '入庫登録')

@section('content')


    <h2>入庫登録</h2>

    <form action="{{ route('stocks.in.store') }}" method="POST">
        @csrf

        <div>
            <label for="item_id">商品</label>

            <select id="item_id" name="item_id">
                <option value="">選択してください</option>

                @foreach ($items as $item)
                    <option
                        value="{{ $item->id }}"
                        @selected(old('item_id') == $item->id)
                    >
                        {{ $item->name }}
                        ({{ $item->sku }})
                    </option>
                @endforeach
            </select>

            @error('item_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="qty">数量</label>

            <input
                id="qty"
                type="number"
                name="qty"
                value="{{ old('qty') }}"
                step="0.01"
                min="0.01"
            >

            @error('qty')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="acted_at">作業日</label>

            <input
                id="acted_at"
                type="date"
                name="acted_at"
                value="{{ old('acted_at', date('Y-m-d')) }}"
            >

            @error('acted_at')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="note">メモ</label>

            <textarea
                id="note"
                name="note"
            >{{ old('note') }}</textarea>

            @error('note')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">入庫を登録</button>
    </form>

    <p>
        <a href="{{ route('items.index') }}">商品登録へ戻る</a>
    </p>

    @endsection
