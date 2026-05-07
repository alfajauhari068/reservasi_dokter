<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\ReservasiController as DokterReservasiController;
use App\Http\Controllers\Dokter\ScheduleController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pasien\ReservasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug', function () {
    return [
        'auth_check' => \Illuminate\Support\Facades\Auth::check(),
        'user' => \Illuminate\Support\Facades\Auth::user(),
        'session_id' => session()->getId(),
    ];
});

Route::get('/debug-logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return 'Logged out';
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.perform')
    ->middleware('guest');

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Register routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.perform')
    ->middleware('guest');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

Route::resource('admin/doctors', \App\Http\Controllers\DoctorsController::class)
    ->names('admin.doctors')
    ->middleware(['auth', 'role:admin']);

Route::get('/dokter/dashboard', [DokterDashboardController::class, 'index'])
    ->name('dokter.dashboard')
    ->middleware(['auth', 'role:dokter']);

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('reservasi/riwayat', [DokterReservasiController::class, 'history'])->name('reservasi.history');
    Route::get('reservasi/{appointment}', [DokterReservasiController::class, 'show'])
        ->whereNumber('appointment')
        ->name('reservasi.show');
    Route::put('reservasi/{appointment}', [DokterReservasiController::class, 'update'])
        ->whereNumber('appointment')
        ->name('reservasi.update');

    Route::resource('schedule', ScheduleController::class);
});

Route::get('/pasien/dashboard', [PasienDashboardController::class, 'index'])
    ->name('pasien.dashboard')
    ->middleware(['auth', 'role:pasien']);

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('reservasi', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('reservasi/riwayat', [ReservasiController::class, 'history'])->name('reservasi.history');
    Route::get('reservasi/{appointment}', [ReservasiController::class, 'show'])->name('reservasi.show');
});
