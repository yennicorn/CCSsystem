<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EndUser\ApplicationController;
use App\Http\Controllers\EndUser\HomepageController;
use App\Http\Controllers\Master\BackupController;
use App\Http\Controllers\Master\DecisionController;
use App\Http\Controllers\Master\MasterDashboardController;
use App\Http\Controllers\Master\ReportController;
use App\Http\Controllers\Master\AuditLogController;
use App\Http\Controllers\Master\SettingsController;
use App\Http\Controllers\Master\UserManagementController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\WelcomeController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'super_admin' => redirect('/super-dashboard'),
        'admin' => redirect('/admin-dashboard'),
        default => redirect('/homepage'),
    };
})->name('dashboard');
Route::get('/home', fn () => redirect()->route('dashboard'));
Route::get('/master-dashboard/{any?}', function (?string $any = null) {
    $target = '/super-dashboard';

    if ($any) {
        $target .= '/'.$any;
    }

    return redirect($target);
})->where('any', '.*');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    })->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*[;:"\'\/\.])(?=\S+$).{8,20}$/'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'force_password_change' => false,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    })->name('password.update');
});

// Logout must always resolve to welcome page even if session is already expired.
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'form'])->name('password.change.form');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

Route::middleware(['auth', 'active', 'force.password.change', 'role:parent,student'])
    ->prefix('homepage')
    ->group(function () {
        Route::get('/', [HomepageController::class, 'index'])->name('homepage');
        Route::get('/feed', [HomepageController::class, 'feed'])->name('homepage.feed');
        Route::get('/enrollment', [HomepageController::class, 'enrollment'])->name('homepage.enrollment');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::put('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    });

Route::middleware(['auth', 'active', 'force.password.change', 'role:admin'])
    ->prefix('admin-dashboard')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/applications', [AdminDashboardController::class, 'applications'])->name('applications.index');
        Route::get('/monitoring', [AdminDashboardController::class, 'monitoring'])->name('monitoring');
        Route::get('/monitoring/{application}', [AdminDashboardController::class, 'showMonitoringApplication'])->name('monitoring.show');
        Route::post('/applications/{application}/review', [ReviewController::class, 'review'])->name('applications.review');
        Route::resource('announcements', AnnouncementController::class)->except(['show']);
    });

Route::middleware(['auth', 'active', 'force.password.change', 'role:super_admin'])
    ->prefix('super-dashboard')
    ->name('master.')
    ->group(function () {
        Route::get('/', [MasterDashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [MasterDashboardController::class, 'monitoring'])->name('monitoring');
        Route::get('/monitoring/{application}', [MasterDashboardController::class, 'showMonitoringApplication'])->name('monitoring.show');
        Route::post('/monitoring/{application}/unlock-edit', [MasterDashboardController::class, 'unlockMonitoringEdit'])->name('monitoring.unlock-edit');
        Route::put('/monitoring/{application}', [MasterDashboardController::class, 'updateMonitoringApplication'])->name('monitoring.update');
        Route::get('/enrollment', [MasterDashboardController::class, 'enrollment'])->name('enrollment');
        Route::get('/school-years', [MasterDashboardController::class, 'schoolYears'])->name('school-years.index');
        Route::get('/backup', [MasterDashboardController::class, 'backup'])->name('backup.index');
        Route::post('/applications/{application}/decision', [DecisionController::class, 'decide'])->name('applications.decide');
        Route::post('/school-years/{schoolYear}/toggle-enrollment', [MasterDashboardController::class, 'toggleEnrollment'])->name('school-years.toggle');
        Route::post('/school-years/{schoolYear}/set-active', [MasterDashboardController::class, 'setActive'])->name('school-years.set-active');
        Route::resource('announcements', AnnouncementController::class)->except(['show']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::post('/backup/database', [BackupController::class, 'download'])->name('backup.database');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.update-role');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    });
