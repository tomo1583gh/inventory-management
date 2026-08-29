@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')

<div class="auth-container">

    <h2>ユーザー登録</h2>

    <form 
        action="{{ route('register') }}" 
        method="POST"
        class="form-card auth-card"
    >
        @csrf

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="form-group">
            <label for="name">
                ユーザー名
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="email">
                メールアドレス
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
            >
        </div>

        <div class="form-group">
            <label for="password">
                パスワード
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            >
        </div>

        <div class="form-group">
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
        
        <div class="form-actions">
            <button
                type="submit"
                class="btn btn-primary"
            >
                登録
            </button>
        </div>
    </form>

    <p class="auth-register">
        <a href="{{ route('login') }}">
            ログイン画面へ戻る
        </a>
    </p>
</div>

@endsection