<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Password</title>
<link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="auth-portal">
    <section class="auth-left">
        <div class="auth-brand">
            <div class="auth-seal">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School seal logo">
            </div>
            <h1> Password Update</h1>
            <p>For security, your account requires a fresh password before continuing.</p>
        </div>
    </section>

    <section class="auth-right">
        <div class="auth-shell-card auth-compact">
            <div class="auth-shell-head">
                <h2> Password Update Required</h2>
                <p>Please set a new secure password to continue.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}">
                @csrf
                <label>New Password</label>
                <input type="password" name="password" required>

                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>

                <button class="btn btn-auth mt-12" type="submit"> Update Password</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
