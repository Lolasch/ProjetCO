<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Login</h1>

    @if(session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</body>
</html>
