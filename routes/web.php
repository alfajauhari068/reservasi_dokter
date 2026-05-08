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

// Public Routes (no authentication required)
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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.perform');
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin Resource Routes
        Route::resource('doctors', \App\Http\Controllers\DoctorsController::class);

        // Admin Queue Management
        Route::prefix('queues')->name('queues.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QueueController::class, 'index'])->name('index');
            Route::post('/{queue}/status', [\App\Http\Controllers\Admin\QueueController::class, 'updateStatus'])->name('update-status');
            Route::post('/reset', [\App\Http\Controllers\Admin\QueueController::class, 'resetTodayQueues'])->name('reset');
            Route::post('/generate', [\App\Http\Controllers\Admin\QueueController::class, 'generateQueues'])->name('generate');
            Route::get('/updates', [\App\Http\Controllers\Admin\QueueController::class, 'getQueueUpdates'])->name('updates');
        });

        // Admin Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/visitation', [App\Http\Controllers\Admin\VisitReportController::class, 'index'])->name('visitation');
            Route::get('/visitation/export-pdf', [App\Http\Controllers\Admin\VisitReportController::class, 'exportPdf'])->name('visitation.export.pdf');
            Route::get('/visitation/export-excel', [App\Http\Controllers\Admin\VisitReportController::class, 'exportExcel'])->name('visitation.export.excel');
            Route::post('/visitation/export', [App\Http\Controllers\Admin\VisitReportController::class, 'export'])->name('visitation.export');

            // Example Filter Routes (commented out)
            // Route::get('/filter-example', [App\Http\Controllers\Admin\FilterExampleController::class, 'indexWithFilterExample'])->name('filter.example');
            Route::get('/filter-form', [App\Http\Controllers\Admin\FilterExampleController::class, 'filterFormOnly'])->name('filter.form');
        });
    });

/*
|--------------------------------------------------------------------------
| Doctor Routes (Role: Dokter)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dokter'])->group(function () {
    Route::get('/dokter/dashboard', [DokterDashboardController::class, 'index'])->name('dokter.dashboard');

    Route::prefix('dokter')->name('dokter.')->group(function () {
        // Doctor Reservation Management
        Route::get('reservasi/riwayat', [DokterReservasiController::class, 'history'])->name('reservasi.history');
        Route::get('reservasi/{appointment}', [DokterReservasiController::class, 'show'])
            ->whereNumber('appointment')
            ->name('reservasi.show');
        Route::put('reservasi/{appointment}', [DokterReservasiController::class, 'update'])
            ->whereNumber('appointment')
            ->name('reservasi.update');

        // Doctor Schedule Management
        Route::resource('schedule', ScheduleController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Patient Routes (Role: Pasien)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/pasien/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');

    Route::prefix('pasien')->name('pasien.')->group(function () {
        // Patient Reservation Management
        Route::get('reservasi', [ReservasiController::class, 'create'])->name('reservasi.create');
        Route::post('reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
        Route::get('reservasi/riwayat', [ReservasiController::class, 'history'])->name('reservasi.history');
        Route::get('reservasi/{appointment}', [ReservasiController::class, 'show'])->name('reservasi.show');
    });
});
