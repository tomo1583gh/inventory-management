@extends('layouts.app')

@section('title', '商品一覧')

@section('content')


    <h2>商品一覧</h2>

    <p>
        ログイン中
        {{ auth()->user()->name }}
    </p>

    <form 
        action="{{ route('logout') }}"
        method="POST"
    >
        @csrf

        <button type="submit">
            ログアウト
        </button>
    </form>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('items.create') }}">
        商品を登録する
    </a>

    <form action="{{ route('items.index') }}" method="GET"> 
        
        <div>
            <label for="category_id">カテゴリー</label>

            <select id="category_id" name="category_id">

                <option value="">
                    すべて
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        @selected($categoryId == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="q">商品名</label>

            <input
                id="q"
                type="text"
                name="q"
                value="{{ $q }}"
            >
        </div>

        <div>
            <label for="sku">管理番号</label>

            <input
                id="sku"
                type="text"
                name="sku"
                value="{{ $sku ?? '' }}"
            >
        </div>

        <button type="submit">
            検索
        </button>

        <a href="{{ route('items.index') }}">
            リセット
        </a>

    </form>

    @if ($items->isEmpty())
        <p>商品が登録されていません。</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>カテゴリー</th>
                    <th>商品名</th>
                    <th>管理番号</th>
                    <th>単位</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->category?->name ?? '未設定' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <a href="{{ route('items.edit', $item) }}">
                                編集
                            </a>

                            <form
                                action="{{ route('items.destroy', $item) }}"
                                method="POST"
                                style="display: inline;"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('この商品を削除しますか？')"
                                >
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $items->links() }}
    @endif

@endsection

