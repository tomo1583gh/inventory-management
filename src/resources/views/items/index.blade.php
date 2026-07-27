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

        <div>
            <label for="direction">順序</label>

            <select id="direction" name="direction">
                <option value="asc" @selected($direction == 'asc')>
                    昇順
                </option>

                <option value="desc" @selected($direction == 'desc')>
                    降順
                </option>
            </select>
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

        @php
            $nextDirection = $direction === 'asc' ? 'desc' : 'asc';
        @endphp

        <table border="1">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('items.index', array_merge(request()->query(), [
                            'sort' => 'created_at',
                            'direction' => $sort === 'created_at'
                                ? $nextDirection
                                : 'desc',
                            'page' => 1,
                        ])) }}">
                            登録日

                            @if ($sort === 'created_at')
                                {{ $direction === 'asc' ? '▲' : '▼' }}
                            @endif
                        </a>
                    </th>
                    <th>ID</th>
                    <th>
                        <a href="{{ route('items.index', array_merge(request()->query(),[
                        'sort' => 'category',
                        'direction' => $sort === 'category'
                            ? $nextDirection
                            : 'asc',
                        'page' => 1,
                        ])) }}">
                            カテゴリー
                        
                            @if ($sort === 'category')
                                {{ $direction === 'asc' ? '▲' : '▼' }}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('items.index', array_merge(request()->query(),[
                            'sort' => 'name',
                            'direction' => $sort === 'name'
                                ? $nextDirection
                                : 'asc',
                            'page' => 1,
                        ])) }}">
                            商品名
                        
                        @if ($sort === 'name')
                            {{ $direction === 'asc' ? '▲' : '▼' }}
                        @endif
                        </a>
                    </th>
                    <th>管理番号</th>
                    <th>単位</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->created_at->format('Y/m/d') }}</td>
                        <td>{{ $item->id }}</td>
                        <td class="text-center">{{ $item->category?->name ?? '未設定' }}</td>
                        <td class="text-center">
                            <a href="{{ route('items.show', $item) }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="text-center">{{ $item->sku }}</td>
                        <td class="text-center">{{ $item->unit }}</td>
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

