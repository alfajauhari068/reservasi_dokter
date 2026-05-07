{{--
    DEMO PENGGUNAAN TABEL ANTRIAN DENGAN PAGINATION & SEARCH

    File ini menunjukkan contoh lengkap implementasi tabel antrian
    dengan fitur pencarian dan pagination.

    CARA MENJALANKAN DEMO:
    1. Uncomment salah satu route di routes/web.php
    2. Akses URL yang sesuai
    3. Test fitur pencarian dan pagination

    CONTOH ROUTE YANG BISA DIGUNAKAN:

    // Untuk method dengan Eloquent
    Route::get('admin/queues/search', [AdminQueueController::class, 'dailyQueueTableWithSearch'])
        ->name('admin.queues.search')
        ->middleware(['auth', 'role:admin']);

    // Untuk method optimized dengan Query Builder
    Route::get('admin/queues/optimized', [AdminQueueController::class, 'dailyQueueTableOptimizedWithSearch'])
        ->name('admin.queues.optimized')
        ->middleware(['auth', 'role:admin']);

    URL DEMO:
    - http://localhost:8000/admin/queues/search
    - http://localhost:8000/admin/queues/search?search=john
    - http://localhost:8000/admin/queues/search?search=john&per_page=5&page=1

    FITUR YANG BISA DITEST:
    ✅ Pencarian berdasarkan nama pasien
    ✅ Pencarian berdasarkan nama dokter
    ✅ Pencarian berdasarkan kode booking
    ✅ Pencarian berdasarkan nomor antrian
    ✅ Pagination dengan berbagai ukuran halaman
    ✅ Reset pencarian
    ✅ Navigasi halaman dengan parameter tersimpan
--}}

{{-- Tampilan demo tidak ada konten khusus, gunakan table_example.blade.php --}}