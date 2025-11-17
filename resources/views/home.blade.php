<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ProjetCO')</title>

    <!-- Préconnexion et import de la police Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS compilé avec Vite -->
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex flex-col font-sans" style="background: linear-gradient(120deg,#11101a 60%,#23235b 100%);">
    <!-- Main content -->
    <main class="flex-grow flex flex-col items-center justify-center px-4">
        <h1 class="font-extrabold text-4xl md:text-5xl mb-4 text-center" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">
            Bienvenue sur ProjetCO
        </h1>

        <p class="text-base md:text-lg mb-8 text-center max-w-xl" style="color: #d5dff5;">
            Un site de gestion de projets où l'on peut collaborer en équipe.
        </p>

        @if (session('success'))
            <p class="font-bold text-green-400 mb-6">{{ session('success') }}</p>
        @endif

        <div class="flex space-x-4">
            <a href="{{ route('login') }}"
               class="inline-block px-8 py-3 rounded-lg font-semibold shadow transition text-lg"
               style="background: #5b6cb2; color: white; border: 2px solid #5b6cb2;"
               onmouseover="this.style.background='#43519e'; this.style.borderColor='#43519e';"
               onmouseout="this.style.background='#5b6cb2'; this.style.borderColor='#5b6cb2';">
               Connexion
            </a>
            <a href="{{ route('register') }}"
               class="inline-block px-8 py-3 rounded-lg font-semibold shadow transition text-lg"
               style="background: transparent; color: #d5dff5; border: 2px solid #b1b9ea;"
               onmouseover="this.style.background='rgba(177, 185, 234, 0.1)'; this.style.borderColor='#d5dff5';"
               onmouseout="this.style.background='transparent'; this.style.borderColor='#b1b9ea';">
               Inscription
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-4 mt-12 text-center text-sm" style="background: #979fcf; color: #39259c;">
        © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
    </footer>

</body>
</html>
