<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="auth-portal">
    <section class="auth-left">
        <div class="auth-brand">
            <div class="auth-seal">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School seal logo">
            </div>
            <h1> Cabugbugan Community School</h1>
        </div>
    </section>

    <section class="auth-right">
        <div class="auth-shell-card">
            <div class="auth-shell-head">
                <h2> Sign In</h2>
                <p>Enter your credentials to continue.</p>
            </div>
        @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email or Username</label>
            <input type="text" name="email" value="{{ old('email') }}" required>
            <label>Password</label>
            <input type="password" name="password" required>

            <div class="auth-meta">
                <label class="auth-check">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            <button class="btn btn-auth" type="submit"> Sign In</button>
        </form>

            <p class="auth-foot">No account yet? <a href="{{ route('register') }}"> Register</a></p>
        </div>
    </section>
    </div>
</body>
</html>

