<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('index') }}">Home</a>
            @auth
                <a href="{{ route('posts.create') }}">Create Post</a>
                <a id="logout-navlink" href="{{ route('logout') }}">Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const $logoutNavlink = document.getElementById('logout-navlink');
                const $logoutForm = document.getElementById('logout-form');
                
                $logoutNavlink.addEventListener('click', (event) => {
                    event.preventDefault();

                    $logoutForm.submit();
                })
            });
        </script>
    @endauth
    @stack('scripts')
</body>
</html>
