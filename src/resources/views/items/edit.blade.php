@extends('layouts.app')

@section('title', '商品登録')

@section('content')


    <h2>商品編集</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form 
        action="{{ route('items.update', $item) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

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
                        @selected(
                            old('category_id', $item->category_id) == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name">商品名</label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $item->name) }}"
            >

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sku">管理番号</label>

            <input
                id="sku"
                type="text"
                name="sku"
                value="{{ old('sku', $item->sku) }}"
            >

            @error('sku')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit">単位</label>

            <input
                id="unit"
                type="text"
                name="unit"
                value="{{ old('unit', $item->unit) }}"
            >

            @error('unit')
                <p>{{ $message }}</p>
            @enderror
            
        </div>

        <div>
            <label for="minimum_stock">最低在庫数</label>

            <input
                type="number"
                id="minimum_stock"
                name="minimum_stock"
                min="0"
                value="{{ old('minimum_stock', $item->minimum_stock) }}"
            >

            @error('minimum_stock')
                <P>{{ $message }}</P>
            @enderror
        </div>

        <div>
            <label for="note">商品メモ</label>

            <textarea
                id="note"
                name="note"
                rows="4"
            >{{ old('note', $item->note) }}
            </textarea>

            @error('note')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">
            更新
        </button>
    </form>

    <p>
        <a href="{{ route('items.index') }}">
            商品一覧へ戻る
        </a>
    </p>

@endsection
