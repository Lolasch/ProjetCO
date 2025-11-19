<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col relative overflow-x-hidden" style="background: linear-gradient(120deg,#11101a 60%,#23235b 100%);">
    <main class="flex-grow flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md rounded-xl shadow-2xl p-8" style="background: rgba(20, 18, 50, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(177, 185, 234, 0.2);">
            <h1 class="text-2xl md:text-3xl font-bold text-center mb-6" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">Inscription</h1>
            @if ($errors->any())
                <div class="font-bold text-red-400 text-center mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-semibold mb-2" style="color: #d5dff5;">Nom :</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2"
                        style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                        placeholder="Votre nom"
                    >
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold mb-2" style="color: #d5dff5;">E-mail :</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2"
                        style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                        placeholder="votre@email.com"
                    >
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold mb-2" style="color: #d5dff5;">Mot de passe :</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2"
                        style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                        placeholder="••••••••"
                    >
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold mb-2" style="color: #d5dff5;">Confirmer le mot de passe :</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2"
                        style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                        placeholder="••••••••"
                    >
                </div>
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg font-semibold shadow transition text-lg cursor-pointer hover:shadow-lg"
                    style="background: #5b6cb2; color: white; border: none;"
                    onmouseover="this.style.background='#43519e';"
                    onmouseout="this.style.background='#5b6cb2';"
                >
                    S'inscrire
                </button>
            </form>
            <a href="{{ route('login') }}"
               class="block mt-7 text-center font-semibold transition text-base"
               style="color: #b1b9ea;"
               onmouseover="this.style.color='#d5dff5';"
               onmouseout="this.style.color='#b1b9ea';">
                Déjà inscrit ? Connecte-toi ici
            </a>
        </div>
    </main>
    <div class="px-8 py-4">
        <a href="{{ url()->previous() }}"
           class="inline-block px-6 py-2 rounded-lg font-semibold shadow transition text-base cursor-pointer"
           style="background: rgba(177, 185, 234, 0.2); color: #b1b9ea; border: 1px solid rgba(177, 185, 234, 0.5);"
           onmouseover="this.style.background='rgba(177, 185, 234, 0.3)'; this.style.borderColor='#b1b9ea';"
           onmouseout="this.style.background='rgba(177, 185, 234, 0.2)'; this.style.borderColor='rgba(177, 185, 234, 0.5)';">
            ← Retour
        </a>
    </div>

    <footer class="w-full py-4 text-center text-sm" style="background: #979fcf; color: #39259c;">
        © 2025 – SAE 501 – Application de gestion de projet — Lola Schmitt
    </footer>
</body>
</html>
