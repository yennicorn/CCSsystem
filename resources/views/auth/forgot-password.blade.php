<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="auth-portal">
    <section class="auth-left">
        <div class="auth-brand">
            <div class="auth-seal">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School seal logo">
            </div>
            <h1> Account Recovery</h1>
            <p>Reset your access securely using your registered email address.</p>
        </div>
    </section>

    <section class="auth-right">
        <div class="auth-shell-card auth-compact">
            <div class="auth-shell-head">
                <h2> Forgot Password</h2>
                <p>Enter your registered email to receive a reset link.</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                <button class="btn btn-auth mt-12" type="submit"> Send Reset Link</button>
            </form>

            <p class="auth-foot"><a href="{{ route('login') }}"> Back to Login</a></p>
        </div>
    </section>
</div>
</body>
</html>
