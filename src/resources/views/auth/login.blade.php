@extends('layouts.app')

@section('title', 'ログイン')

@section('content')

<div class="auth-container">

    <h2>ログイン</h2>

    @if (config('app.demo_user_email') && config('app.demo_user_password'))
        <div class="demo-login">
            <p>
                <strong>デモアカウント</strong><br>
                このシステムをお試しいただけます。
            </p>

            <p>
                メールアドレス：
                {{ config('app.demo_user_email') }}<br>

                パスワード：
                {{ config('app.demo_user_password') }}
            </p>

            <button
                type="button"
                class="btn btn-secondary demo-fill-button"
                onclick="fillDemoAccount()"
            >
                デモアカウントを入力
            </button>
        </div>
    @endif

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

    {{--  
    <p class="auth-register"> 
        アカウントをお持ちでない方は
        <a href="{{ route('register') }}">
            ユーザー登録
        </a>
    </p> 
    --}}

    {{-- 後で管理者だけがユーザー登録できる方式に変更 --}}

</div>

<script>
    function fillDemoAccount() {
        document.getElementById('email').value =
            @json(config('app.demo_user_email'));

        document.getElementById('password').value =
            @json(config('app.demo_user_password'));
    }
</script>

@endsection
