<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-[#f4f4f9] flex flex-col">

    <!-- Header (branding ultra visible à gauche) -->
    <header class="flex items-center px-8 py-6">
        <span class="text-5xl md:text-6xl font-extrabold text-[#131861] tracking-tight">ProjetCO</span>
    </header>

    <!-- Main content vertical centrée -->
    <main class="flex-grow flex flex-col items-center justify-center">
        <h1 class="text-3xl md:text-4xl font-bold text-[#131861] mb-4 text-center">Bienvenue sur ProjetCO.</h1>
        <p class="text-base md:text-lg text-[#153959] mb-8 text-center max-w-xl">
            Un site de gestion de projets où l'on peut collaborer en équipe.
        </p>
        @if (session('success'))
            <p class="font-bold text-green-600 mb-6">{{ session('success') }}</p>
        @endif
        <div class="flex space-x-4">
            <a href="{{ route('login') }}"
                class="inline-block px-8 py-3 rounded-xl bg-[#5b6cb2] text-white font-semibold shadow transition hover:bg-[#43519e] text-lg">Connexion</a>
            <a href="{{ route('register') }}"
               class="inline-block px-8 py-3 rounded-xl bg-[#8ed2f6] text-[#153959] font-semibold shadow transition hover:bg-[#6bbfde] text-lg">Inscription</a>
        </div>
    </main>

    <!-- Footer fond bleu -->
    <footer class="w-full py-4 mt-12 text-center text-sm text-white" style="background-color: #5b6cb2;">
        © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
    </footer>
</body>
</html>
