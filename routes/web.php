<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
    ->middleware(['auth', 'role:admin']);

// Admin Queue Routes
Route::prefix('admin/queues')->name('admin.queues.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\QueueController::class, 'index'])
        ->name('index');
    Route::post('/{queue}/status', [\App\Http\Controllers\Admin\QueueController::class, 'updateStatus'])
        ->name('update-status');
    Route::post('/reset', [\App\Http\Controllers\Admin\QueueController::class, 'resetTodayQueues'])
        ->name('reset');
    Route::post('/generate', [\App\Http\Controllers\Admin\QueueController::class, 'generateQueues'])
        ->name('generate');
    Route::get('/updates', [\App\Http\Controllers\Admin\QueueController::class, 'getQueueUpdates'])
        ->name('updates');
});

Route::resource('admin/doctors', \App\Http\Controllers\DoctorsController::class)
    ->names('admin.doctors')
    ->middleware(['auth', 'role:admin']);

// CONTOH ROUTE UNTUK TABEL ANTRIAN HARIAN
// Tambahkan route ini jika ingin menggunakan tabel antrian sederhana
// Route::get('admin/queues/table', [AdminQueueController::class, 'dailyQueueTable'])
//     ->name('admin.queues.table')
//     ->middleware(['auth', 'role:admin']);

// CONTOH ROUTE UNTUK TABEL ANTRIAN DENGAN SEARCH & PAGINATION
// Route::get('admin/queues/search', [AdminQueueController::class, 'dailyQueueTableWithSearch'])
//     ->name('admin.queues.search')
//     ->middleware(['auth', 'role:admin']);

// CONTOH ROUTE UNTUK TABEL ANTRIAN OPTIMIZED DENGAN SEARCH & PAGINATION
// Route::get('admin/queues/optimized', [AdminQueueController::class, 'dailyQueueTableOptimizedWithSearch'])
//     ->name('admin.queues.optimized')
//     ->middleware(['auth', 'role:admin']);

// CONTOH ROUTE UNTUK FORM FILTER LAPORAN KUNJUNGAN
// Route::get('admin/reports/filter-example', [App\Http\Controllers\Admin\FilterExampleController::class, 'indexWithFilterExample'])
//     ->name('admin.reports.filter.example')
//     ->middleware(['auth', 'role:admin']);

// CONTOH ROUTE UNTUK FORM FILTER SAJA (TANPA TABEL)
Route::get('admin/reports/filter-form', [App\Http\Controllers\Admin\FilterExampleController::class, 'filterFormOnly'])
    ->name('admin.reports.filter.form')
    ->middleware(['auth', 'role:admin']);

// Admin Reports Routes
Route::prefix('admin/reports')->name('admin.reports.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/visitation', [App\Http\Controllers\Admin\VisitReportController::class, 'index'])
        ->name('visitation');
    Route::get('/visitation/export-pdf', [App\Http\Controllers\Admin\VisitReportController::class, 'exportPdf'])
        ->name('visitation.export.pdf');
    Route::get('/visitation/export-excel', [App\Http\Controllers\Admin\VisitReportController::class, 'exportExcel'])
        ->name('visitation.export.excel');
    Route::post('/visitation/export', [App\Http\Controllers\Admin\VisitReportController::class, 'export'])
        ->name('visitation.export');
});

Route::get('/dokter/dashboard', [DokterDashboardController::class, 'index'])
    ->name('dokter.dashboard')
    ->middleware('auth');

Route::get('/pasien/dashboard', [PasienDashboardController::class, 'index'])
    ->name('pasien.dashboard')
    ->middleware('auth');
