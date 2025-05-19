<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', '勤怠管理システム')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap（CDN or local） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            color: #fff;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #000;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .btn, .badge {
            font-weight: bold;
        }

        .container {
            background: #eee;
            color: #000;
            padding: 3rem;
            border-radius: 1rem;
            margin-top: 2rem;
        }
        main {
            flex: 1;
            display: flex;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <!-- ナビバー -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">CodeShift</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                @auth
                    @if (auth()->user()->is_admin)
                    {{-- 管理者用リンク --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.attendance.daily') }}">勤怠一覧</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">スタッフ一覧</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requests.index') }}">申請一覧</a></li>
                    @else
                    {{-- 一般スタッフ用リンク --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('attendance.index') }}">勤怠</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('attendance.monthly') }}">勤怠一覧</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('requests.index') }}">申請</a></li>
                    @endif
                @endauth
                {{-- ログアウト共通 --}}
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="display: inline;">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- メインコンテンツ -->
    <main class="py-4">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('script')
</body>
</html>
