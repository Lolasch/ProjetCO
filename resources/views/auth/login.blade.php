<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-[#f4f4f9] flex flex-col relative overflow-x-hidden">

    <!-- Header branding à gauche -->
    <header class="flex items-center px-8 py-6">
        <span class="text-5xl md:text-6xl font-extrabold text-[#131861] tracking-tight">ProjetCO</span>
    </header>

    <!-- Main form centré verticalement -->
    <main class="flex-grow flex flex-col items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-xl shadow p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-[#131861] text-center mb-6">Connexion</h1>
            @if(session('error'))
                <div class="font-bold text-[#e17596] text-center mb-4">{{ session('error') }}</div>
            @endif
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-[#153959] mb-2">E-mail :</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 rounded-lg border border-[#b1cdf3] bg-[#f6f7fb] text-[#131861] focus:outline-none focus:ring-2 focus:ring-[#5b6cb2]"
                    >
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-[#153959] mb-2">Mot de passe :</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 rounded-lg border border-[#b1cdf3] bg-[#f6f7fb] text-[#131861] focus:outline-none focus:ring-2 focus:ring-[#5b6cb2]"
                    >
                </div>
                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-[#5b6cb2] text-white font-semibold shadow transition hover:bg-[#43519e] text-lg"
                >
                    Se connecter
                </button>
            </form>
            <a href="{{ route('register') }}"
               class="block mt-7 text-center text-[#8ed2f6] font-semibold hover:text-[#6bbfde] transition">
                Pas encore de compte ? Inscris-toi ici
            </a>
        </div>
    </main>

    <!-- Bouton retour en bas à gauche, bien flotant au-dessus du footer -->
    <a href="{{ url()->previous() }}"
       class="fixed left-8 bottom-8 z-50 px-6 py-2 rounded-xl bg-[#b7e2fa] text-[#153959] font-semibold shadow transition hover:bg-[#8ed2f6] text-base">
        ← Retour
    </a>

    <!-- Footer bleu tout en bas -->
    <footer class="w-full py-4 mt-12 text-center text-sm text-white" style="background-color: #5b6cb2;">
        © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
    </footer>
</body>
</html>
