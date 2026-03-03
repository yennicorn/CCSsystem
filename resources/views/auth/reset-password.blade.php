<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="auth-portal">
    <section class="auth-left">
        <div class="auth-brand">
            <div class="auth-seal">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School seal logo">
            </div>
            <h1> Secure Reset</h1>
            <p>Create a new password that is strong and easy for you to remember.</p>
        </div>
    </section>

    <section class="auth-right">
        <div class="auth-shell-card auth-compact">
            <div class="auth-shell-head">
                <h2> Set a New Password</h2>
                <p>Enter your account email and your new password.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required>

                <label>New Password</label>
                <input type="password" name="password" required>

                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" required>

                <button class="btn btn-auth mt-12" type="submit"> Reset Password</button>
            </form>

            <p class="auth-foot"><a href="{{ route('login') }}"> Back to Login</a></p>
        </div>
    </section>
</div>
</body>
</html>
