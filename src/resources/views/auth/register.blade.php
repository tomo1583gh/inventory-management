<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>ユーザー登録</title>
</head>

<body>
    <h1>在庫管理アプリ</h1>
    <h2>ユーザー登録</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form 
        action="{{ route('register') }}" 
        method="POST"
    >
        @csrf

        <div>
            <label for="name">ユーザー名</label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
            >
        </div>

        <div>
            <label for="email">メールアドレス</label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus="email"
            >
        </div>

        <div>
            <label for="password">パスワード</label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autofocus="password"
            >
        </div>

        <div>
            <label for="password_confirmation">
                パスワード確認
            </label>
            
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            >
        </div>
        
        <button type="submit">
            登録
        </button>
    </form>

    <p>
        <a href="{{ route('login') }}">
            ログイン画面へ戻る
        </a>
    </p>
</body>
</html>