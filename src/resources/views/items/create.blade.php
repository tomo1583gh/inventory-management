@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品登録')

@section('content')


    <h2>商品登録</h2>

    <form 
        action="{{ route('items.store') }}" 
        method="POST"
    >
        @csrf

        <div>
            <label for="category_id">カテゴリー</label>

            <select
                id="category_id"
                name="category_id"
            >

                <option value="">選択してください</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >

                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name">商品名</label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
            >

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sku">管理番号</label>

            <input
                id="sku"
                type="text"
                name="sku"
                value="{{ old('sku') }}"
            >
            
            @error('sku')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit">単位</label>

            <input
                id="unit"
                type="text"
                name="unit"
                value="{{ old('unit') }}"
                placeholder="袋、㎏、Lなど"
            >

            @error('unit')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="minimum_stock">最低在庫数</label>

            <input
                type="number"
                id="minimum_stock"
                name="minimum_stock"
                min="0"
                value="{{ old('minimum_stock', 0) }}"
            >

            @error('minimum_stock')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-area"  for="note">商品メモ</label>

            <textarea
                id="note"
                name="note"
                rows="4"
            >{{ old('note') }}
            </textarea>

            @error('note')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">
            登録
        </button>
    </form>

    <p>
        <a href="{{ route('items.index') }}">
            商品一覧に戻る
        </a>
    </p>

    @endsection
