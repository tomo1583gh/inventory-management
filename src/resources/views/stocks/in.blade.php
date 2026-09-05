@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '入庫登録')

@section('content')


    <h2>入庫登録</h2>

    <form 
        action="{{ route('stocks.in.store') }}" 
        method="POST"
        class="form-card"
        >
        @csrf

        <div class="form-group">
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
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
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
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="acted_at">作業日時</label>

            <input
                id="acted_at"
                type="datetime-local"
                name="acted_at"
                value="{{ old('acted_at', now()->format('Y-m-d\TH:i')) }}"
            >

            @error('acted_at')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="note">入庫メモ</label>

            <textarea
                id="note"
                name="note"
                rows="4"
            >{{ old('note') }}</textarea>

            @error('note')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button 
                type="submit"
                class="btn btn-primary"
            >
                入庫を登録
            </button>

            <a
                href="{{ route('stocks.index') }}"
                class="btn-light"
            >
                在庫一覧へ戻る
            </a>
        </div>
    </form>

    @endsection
