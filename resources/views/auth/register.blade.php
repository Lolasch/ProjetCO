<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Register</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label>Name:</label><br>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="password_confirmation"><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</body>
</html>
