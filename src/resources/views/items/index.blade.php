@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

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

    <div class="item-actions">
        <a 
            href="{{ route('items.create') }}"
            class="btn btn-primary"
        >
            商品を登録する
        </a>

        <a 
            href="{{ route('items.import.create')}}"
            class="btn btn-secondary"
        >
            商品CSVインポート
        </a>
    </div>

    <h3 class="search-title">
            検索条件
        </h3>

    <form 
        action="{{ route('items.index') }}" 
        method="GET"
        class="search-form"
    > 
        
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

        <div class="search-actions">
            <button 
                type="submit"
                class="btn btn-primary"
            >
                検索
            </button>

            <a 
                href="{{ route('items.index') }}"
                class="btn-light"
            >
                リセット
            </a>
        </div>

    </form>

    <div class="list-actions">
        <p>
            <a 
                href="{{ route('items.export.csv', [
                    'q' => $q,
                    'sku' => $sku,
                    'category_id' => $categoryId,
                    'sort' => $sort,
                    'direction' => $direction,
                ]) }}"
                class="btn btn-secondary"
            >
                商品一覧CSV出力
            </a>
        </p>
    </div>

    @if ($items->isEmpty())
        <p>商品が登録されていません。</p>
    @else

        @php
            $nextDirection = $direction === 'asc' ? 'desc' : 'asc';
        @endphp

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">
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
                            <td class="text-center">{{ $item->created_at->format('Y/m/d') }}</td>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center">{{ $item->category?->name ?? '未設定' }}</td>
                            <td class="text-left">
                                <a href="{{ route('items.show', $item) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="text-center">{{ $item->sku }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a 
                                        href="{{ route('items.edit', $item) }}"
                                        class="btn btn-light btn-sm"
                                    >
                                        編集
                                    </a>

                                    <form
                                        action="{{ route('items.destroy', $item) }}"
                                        method="POST"
                                        class="action-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('この商品を削除しますか？')"
                                        >
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    @endif

@endsection

