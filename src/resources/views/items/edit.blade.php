<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta 
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>商品編集</title>
</head>

<body>
    <h1>在庫管理アプリ</h1>
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
        <button type="submit">
            更新
        </button>
    </form>

    <p>
        <a href="{{ route('items.index') }}">
            商品一覧へ戻る
        </a>
    </p>
</body>
</html>
