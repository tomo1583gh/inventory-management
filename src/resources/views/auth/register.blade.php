@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')


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

@endsection