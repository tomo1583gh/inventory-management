@extends('layouts.app')

@section('title', '商品登録')

@section('content')


    <h2>商品登録</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

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
                <p>{{ $message }}</p>
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
                <p>{{ $message }}</p>
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
                <p>{{ $message }}</p>
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
                <p>{{ $message }}</p>
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
