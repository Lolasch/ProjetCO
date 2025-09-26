<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            margin-bottom: 30px;
        }
        .buttons {
            display: flex;
            gap: 20px;
        }
        a {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            background: #007bff;
            transition: background 0.3s;
        }
        a:hover {
            background: #0056b3;
        }
        .register {
            background: #28a745;
        }
        .register:hover {
            background: #1e7e34;
        }
    </style>
</head>
<body>
    <h1>Bienvenue</h1>
    @if (session('success'))
    <p style="color: green; font-weight: bold;">
        {{ session('success') }}
    </p>
    @endif

    <div class="buttons">
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}" class="register">Inscription</a>
    </div>

</body>
</html>
