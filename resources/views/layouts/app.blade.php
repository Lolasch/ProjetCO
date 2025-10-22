<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon Application')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <header style="padding:10px; background:#f0f0f0; margin-bottom:20px;">
        <h1>Mon Application</h1>
        @auth
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Déconnexion</button>
            </form>
        @endauth
    </header>

    <main style="padding:10px;">
        @yield('content')
    </main>

    <footer style="padding:10px; background:#f0f0f0; margin-top:20px;">
        <p>© 2025 Mon Application</p>
    </footer>
</body>
</html>
