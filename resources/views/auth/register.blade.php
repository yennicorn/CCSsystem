<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="auth-portal">
    <section class="auth-left">
        <div class="auth-brand">
            <div class="auth-seal">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School seal logo">
            </div>
            <h1> Account Registration</h1>
        </div>
    </section>

    <section class="auth-right">
        <div class="auth-shell-card">
            <div class="auth-shell-head">
                <h2> Create Account</h2>
                <p>Provide accurate details to register.</p>
            </div>
        @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label>Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required>

            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Role</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="parent" {{ old('role')==='parent'?'selected':'' }}>Parent</option>
                <option value="student" {{ old('role')==='student'?'selected':'' }}>Student</option>
            </select>

            <label>Password</label>
            <input id="password" type="password" name="password" required>

            <label>Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <ul id="rules" class="requirements">
                <li id="r1">8-20 characters</li>
                <li id="r2">At least one uppercase and one lowercase letter</li>
                <li id="r3">At least one number</li>
                <li id="r4">No spaces</li>
                <li id="r5">Disallow ; : " ' / .</li>
                <li id="r6">Confirm password matches</li>
            </ul>

            <button id="registerBtn" class="btn btn-auth" type="submit" disabled> Register Account</button>
        </form>

            <p class="auth-foot">Already registered? <a href="{{ route('login') }}"> Sign In</a></p>
        </div>
    </section>
    </div>

<script>
const p=document.getElementById('password');
const c=document.getElementById('password_confirmation');
const b=document.getElementById('registerBtn');
const mark=(id,ok)=>document.getElementById(id).style.color=ok?'#1b6a3a':'#8c2231';
function validate(){
    const s=p.value,m=c.value;
    const len=/^.{8,20}$/.test(s);
    const uc=/[A-Z]/.test(s);
    const lc=/[a-z]/.test(s);
    const num=/\d/.test(s);
    const noSpace=!/\s/.test(s);
    const noBanned=!/[;:"'\/\.]/.test(s);
    const matched=s.length>0&&s===m;

    mark('r1',len);
    mark('r2',uc&&lc);
    mark('r3',num);
    mark('r4',noSpace);
    mark('r5',noBanned);
    mark('r6',matched);

    b.disabled=!(len&&uc&&lc&&num&&noSpace&&noBanned&&matched);
}
p.addEventListener('input',validate);
c.addEventListener('input',validate);
validate();
</script>
</body>
</html>

