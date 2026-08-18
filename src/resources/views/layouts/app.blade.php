<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', '在庫管理システム')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('css')

</head>

<body>

    <header>
        <h1>
            <a href="{{ route('dashboard') }}">
                農業在庫管理システム
            </a>
        </h1>

        @auth
            <nav>
                <ul class="main-nav">
                    <li> 
                        <a 
                            href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        >
                            ダッシュボード
                        </a>
                    </li>

                    <li>
                        <a 
                            href="{{ route('items.index') }}"
                            class="{{ request()->routeIs('items.index') ? 'active' : '' }}"
                        >
                            商品一覧
                        </a>
                    </li>

                    <li>
                        <a 
                            href="{{ route('stocks.index') }}"
                            class="{{ request()->routeIs('stocks.index') ? 'active' : '' }}"
                        >
                            在庫一覧
                        </a>
                    </li>

                    <li>
                        <a 
                            href="{{ route('stocks.in.create') }}"
                            class="{{ request()->routeIs('stocks.in.create') ? 'active' : '' }}"
                        >
                            入庫登録
                        </a>
                    </li>

                    <li>
                        <a 
                            href="{{ route('stocks.out.create') }}"
                            class="{{ request()->routeIs('stocks.out.create') ? 'active' : '' }}"
                        >
                            出庫登録
                        </a>
                    </li>

                    <li>
                        <a 
                            href="{{ route('stocks.logs') }}"
                            class="{{ request()->routeIs('stocks.logs') ? 'active' : '' }}"
                        >
                            入出庫履歴
                        </a>
                    </li>

                    <li>
                        <form 
                            action="{{ route('logout') }}" 
                            method="POST"
                            class="logout-form"
                        >
                            @csrf

                            <button 
                                type="submit"
                                class="logout-button"
                                >
                                    ログアウト
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        @endauth
    </header>

    <hr>

    @if(session('success'))
        <p style="color: green">
            {{ session('success') }}
        </p>
    @endif

    <main class="container">
        @yield('content')
    </main>

</body>
</html>