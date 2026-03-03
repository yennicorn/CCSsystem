<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CCS Parent/Student Portal' }}</title>
    <link rel="stylesheet" href="{{ asset('css/ccs-ui.css') }}">
</head>
<body class="enduser-body">
<div class="enduser-shell">
    <header class="enduser-topnav print-hide">
        <div class="enduser-topnav-inner">
            <a class="enduser-brand" href="{{ route('homepage.feed') }}">
                <img src="{{ asset('images/branding/CCS_logo.png') }}" alt="School logo">
                <div>
                    <strong>Cabugbugan Community School</strong>
                    <small>Information and Online Enrollment System</small>
                </div>
            </a>

            <nav class="enduser-menu" aria-label="Parent and student navigation">
                <a class="enduser-link {{ request()->routeIs('homepage.feed') ? 'active' : '' }}" href="{{ route('homepage.feed') }}">
                    <x-icon name="announcements" /> Information Feed
                </a>
                <a class="enduser-link {{ request()->routeIs('homepage') || request()->routeIs('homepage.enrollment') || request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('homepage.enrollment') }}">
                    <x-icon name="enrollment" /> Enrollment
                </a>
            </nav>

            <div class="enduser-user">
                <div class="enduser-user-meta">
                    <span>Signed in as</span>
                    <strong>{{ auth()->user()->full_name ?? 'User' }}</strong>
                    <small>{{ strtoupper(str_replace('_', ' ', auth()->user()->role ?? 'USER')) }}</small>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="js-logout-form">
                    @csrf
                    <button class="btn btn-secondary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="enduser-main">
        <section class="enduser-pagehead print-hide">
            <div class="container">
                <h1 class="page-title">@yield('page_title', 'Parent/Student Portal')</h1>
                <p class="muted">@yield('page_subtitle', 'Enrollment management and school announcements')</p>
            </div>
        </section>

        <div class="container enduser-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </div>
    </main>
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
