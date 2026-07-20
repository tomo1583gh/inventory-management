<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', '在庫管理システム')</title>
</head>

<body>

  <h1>在庫管理システム</h1>

  <hr>

  @auth
      <nav>
          <a href="{{ route('items.index') }}">商品一覧</a> |
          <a href="{{ route('stocks.index') }}">在庫一覧</a> |
          <a href="{{ route('stocks.in.create') }}">入庫登録</a> |
          <a href="{{ route('stocks.out.create') }}">出庫登録</a> |
          <a href="{{ route('stocks.logs') }}">入出庫履歴</a> |

          <form action="{{ route('logout') }}" method="POST" style="display:inline">
              @csrf
              <button type="submit">ログアウト</button>
          </form>
      </nav>

  <hr>
  @endauth

  @if(session('success'))
      <p style="color: green">
          {{ session('success') }}
      </p>
  @endif

  @yield('content')

</body>
</html>