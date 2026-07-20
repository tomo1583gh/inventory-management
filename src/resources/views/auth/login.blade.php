<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >
    <title>ログイン</title>
</head>

<body>
    <h1>在庫管理アプリ</h1>
    <h2>ログイン</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form 
        action="{{ route('login') }}" 
        method="POST"
    >
        @csrf

        <div>
            <label for="email">メールアドレス</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div>
            <label for="password">パスワード</label>
            <input
                id="password"
                type="password"
                name="password"
                required
            >
        </div>

        <button type="submit">ログイン</button>
    </form>

    <p>
        アカウントをお持ちでない方は
        <a href="{{ route('register') }}">
            ユーザー登録
        </a>
        {{-- 後で管理者だけがユーザー登録できる方式に変更 --}}
    </p>
</body>
</html>
