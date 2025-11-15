<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProjetCO')</title>
    @vite('resources/css/app.css')
</head>
<!-- FOND NOIR PARTOUT : bg-black sur body -->
<body class="min-h-screen bg-black flex flex-col">

    <!-- Header violet/bleu, toujours full width -->
    <header class="flex items-center justify-between py-6 px-10" style="background-color: #5b6cb2;">
        <span class="text-4xl font-extrabold text-white tracking-tight">ProjetCO</span>
        <div class="flex items-center space-x-8">
            <a href="{{ route('notifications.index') }}" class="focus:outline-none relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#f4f4f9" class="w-7 h-7">
                    <path d="M10 2a6 6 0 00-6 6v2.6c0 .486-.178.958-.504 1.317l-.438.452A1 1 0 004 15h12a1 1 0 00.942-1.369l-.438-.451A2.06 2.06 0 0116 10.6V8a6 6 0 00-6-6zm0 16a2 2 0 002-2H8a2 2 0 002 2z" />
                </svg>
                @auth
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                @endauth
            </a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#f4f4f9" viewBox="0 0 24 24" class="w-8 h-8">
                        <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10Zm-7 18a7 7 0 0114 0v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1Z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div
                    x-show="open"
                    @click.away="open = false"
                    class="absolute right-0 z-40 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2"
                    style="display:none"
                    x-transition>
                    <a href="#" class="block px-4 py-3 text-sm text-[#153959] hover:bg-[#e4e3ef] transition">Mon Profil</a>
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-3 text-sm text-[#153959] hover:bg-[#e4e3ef] transition">
                            Déconnexion
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Aucun main, aucun fond clair, aucun padding global -->
    @yield('content')

    <!-- Footer noir aussi pour fondu parfait -->
<footer class="w-full py-4 mt-10 text-center text-sm text-white" style="background-color: #5b6cb2;">
    © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
</footer>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
