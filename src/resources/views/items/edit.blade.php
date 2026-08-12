@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品登録')

@section('content')


    <h2>商品編集</h2>

    <form 
        action="{{ route('items.update', $item) }}"
        method="POST"
        class="form-card"
    >
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="category_id">カテゴリー</label>

            <select
                id="category_id"
                name="category_id"
            >

                <option value="">選択してください</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(
                            old('category_id', $item->category_id) == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">商品名</label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $item->name) }}"
            >

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="sku">管理番号</label>

            <input
                id="sku"
                type="text"
                name="sku"
                value="{{ old('sku', $item->sku) }}"
            >

            @error('sku')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="unit">単位</label>

            <input
                id="unit"
                type="text"
                name="unit"
                value="{{ old('unit', $item->unit) }}"
            >

            @error('unit')
                <p class="error">{{ $message }}</p>
            @enderror
            
        </div>

        <div class="form-group">
            <label for="minimum_stock">最低在庫数</label>

            <input
                type="number"
                id="minimum_stock"
                name="minimum_stock"
                min="0"
                value="{{ old('minimum_stock', $item->minimum_stock) }}"
            >

            @error('minimum_stock')
                <P class="error">{{ $message }}</P>
            @enderror
        </div>

        <div class="form-group">
            <label for="note">商品メモ</label>

            <textarea
                id="note"
                name="note"
                rows="4"
            >{{ old('note', $item->note) }}</textarea>

            @error('note')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button 
                type="submit"
                class="btn btn-primary"
            >
                更新
            </button>

            <a
                href="{{ route('items.index') }}"
                class="btn-light"
            >
                商品一覧に戻る
            </a>
        </div>
    </form>

@endsection
