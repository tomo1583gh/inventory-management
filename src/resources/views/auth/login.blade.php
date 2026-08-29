@extends('layouts.app')

@section('title', 'ログイン')

@section('content')

<div class="auth-container">

    <h2>ログイン</h2>

    <form 
        action="{{ route('login') }}" 
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
            <label for="email">
                メールアドレス
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
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
            >
        </div>
        
        <div class="form-actions">
            <button
                type="submit"
                class="btn btn-primary"
            >
                ログイン
            </button>
        </div>

    </form>

    <p class="auth-register">
        アカウントをお持ちでない方は
        <a href="{{ route('register') }}">
            ユーザー登録
        </a>
        {{-- 後で管理者だけがユーザー登録できる方式に変更 --}}
    </p>

</div>

@endsection
