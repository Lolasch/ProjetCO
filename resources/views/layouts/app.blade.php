<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProjetCO')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex flex-col" style="background: linear-gradient(120deg,#11101a 60%,#23235b 100%);">
<header class="flex items-center justify-between py-4 px-8 w-full" style="background: #b1b9ea;">
    <a href="{{ route('dashboard') }}"
        class="font-extrabold tracking-tight focus:outline-none"
        style="color: #39259c; font-family: 'Poppins', sans-serif; font-size: 2rem; text-decoration: none;">
        ProjetCO
    </a>
    <div class="flex items-center space-x-8">
        <a href="{{ route('notifications.index') }}" class="focus:outline-none relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="color:#39259c" class="w-7 h-7 fill-current">
                <path d="M10 2a6 6 0 00-6 6v2.6c0 .486-.178.958-.504 1.317l-.438.452A1 1 0 004 15h12a1 1 0 00.942-1.369l-.438-.451A2.06 2.06 0 0116 10.6V8a6 6 0 00-6-6zm0 16a2 2 0 002-2H8a2 2 0 002 2z"/>
            </svg>
            @auth
                @php
                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 bg-[#e7b8e8] text-[#39259c] text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow">
                        {{ $unreadCount }}
                    </span>
                @endif
            @endauth
        </a>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="color:#39259c" class="w-8 h-8 fill-current">
                    <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10Zm-7 18a7 7 0 0114 0v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1Z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div
                x-show="open"
                @click.away="open = false"
                class="absolute right-0 z-40 mt-2 w-44 bg-[#e5ebfb] border border-[#b1b9ea] shadow-xl py-2"
                style="display:none"
                x-transition>
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-4 py-3 text-sm hover:bg-[#d5dff5] transition" style="color:#39259c;">
                        Déconnexion
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</header>



    <main class="w-full px-2 md:px-8 xl:px-16 flex-1 mt-4">
        @yield('content')
    </main>


    <!-- Footer unicolore lavande foncé -->
    <footer class="w-full py-4 mt-10 text-center text-xs"
        style="background: #979fcf; color:#39259c;">
        © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
    </footer>


    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
