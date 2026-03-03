<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CCS System' }}</title>
    <link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School logo">
            <div>
                <strong>Cabugbugan Community School</strong>
                <small>Information and Enrollment System</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar')
        </nav>

        <div class="sidebar-footer">
            <p class="muted">Signed in as</p>
            <p><strong>{{ auth()->user()->full_name ?? 'User' }}</strong></p>
            <p class="role-text">{{ strtoupper(str_replace('_', ' ', auth()->user()->role ?? 'USER')) }}</p>
            <form method="POST" action="{{ route('logout') }}" class="js-logout-form">
                @csrf
                <button class="btn btn-secondary w-full" type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <div class="main-area">
        <header class="topbar">
            <div class="topbar-inner">
                <div>
                    <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
                    <p class="muted">@yield('page_subtitle', 'Cabugbugan Community School Management Portal')</p>
                </div>
            </div>
        </header>

        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<div class="logout-modal" id="logoutConfirmModal" aria-hidden="true">
    <div class="logout-modal__backdrop" data-dismiss-logout></div>
    <div class="logout-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle" aria-describedby="logoutConfirmDesc">
        <div class="logout-modal__icon" aria-hidden="true">!</div>
        <h2 id="logoutConfirmTitle">Confirm Logout</h2>
        <p id="logoutConfirmDesc">Are you sure you want to log out from your account?</p>
        <div class="logout-modal__actions">
            <button type="button" class="btn btn-secondary" data-dismiss-logout>Cancel</button>
            <button type="button" class="btn" id="logoutConfirmBtn">Log Out</button>
        </div>
    </div>
</div>
<script>
(() => {
    const modal = document.getElementById('logoutConfirmModal');
    const confirmButton = document.getElementById('logoutConfirmBtn');

    if (!modal || !confirmButton) {
        return;
    }

    const dismissButtons = modal.querySelectorAll('[data-dismiss-logout]');
    const logoutForms = document.querySelectorAll('.js-logout-form');
    let pendingForm = null;

    const openModal = (form) => {
        pendingForm = form;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        confirmButton.focus();
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        pendingForm = null;
    };

    logoutForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.skipConfirm === '1') {
                return;
            }

            event.preventDefault();
            openModal(form);
        });
    });

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) {
            return;
        }

        pendingForm.dataset.skipConfirm = '1';
        pendingForm.submit();
    });

    dismissButtons.forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
</body>
</html>
