<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name') }}</title>
</head>
<body>
    <main>
        <h1>Login</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div>
                <label for="email">Email:</label>
                <br>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <div style="color: red;">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <br>

            <div>
                <label for="password">Password:</label>
                <br>
                <input type="password" id="password" name="password" required>
                @if ($errors->has('password'))
                    <div style="color: red;">{{ $errors->first('password') }}</div>
                @endif
            </div>
            <br>

            <div>
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <br>

            <button type="submit">Login</button>
        </form>
    </main>

    @if (Route::has('password.request'))
        <nav>
            <a href="{{ route('password.request') }}">Forgot Your Password?</a>
        </nav>
    @endif

    <nav>
        <a href="{{ route('index') }}">Go Back</a>
    </nav>
</body>
</html>
