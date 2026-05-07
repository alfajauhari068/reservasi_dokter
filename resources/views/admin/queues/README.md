{{--
PANDUAN PENGGUNAAN TABEL ANTRIAN HARIAN

    File ini berisi contoh markup Blade untuk tabel antrian harian dengan kolom:
    - No Antrian
    - Nama Pasien
    - Dokter
    - Jam Kunjungan/Jadwal
    - Status Antrian

    CARA PENGGUNAAN:
    1. Copy markup dari file table_example.blade.php
    2. Paste ke view yang diinginkan
    3. Pastikan controller mengirim data $queues
    4. Sesuaikan route jika diperlukan

    STRUKTUR DATA YANG DIPERLUKAN:
    $queues harus berisi collection dengan struktur seperti:
    - queue->queue_number (int)
    - queue->appointment->patient->user->name (string)
    - queue->appointment->doctor->user->name (string)
    - queue->appointment->doctor->specialization->name (string)
    - queue->appointment->appointment_time (datetime)
    - queue->appointment->appointment_date (date)
    - queue->queue_status (string: waiting, called, served, skipped)

    CONTOH ROUTE:
    Route::get('/admin/queues/table', [QueueController::class, 'dailyQueueTable'])
        ->middleware(['auth', 'role:admin'])
        ->name('admin.queues.table');

    CONTOH CONTROLLER METHOD:
    public function dailyQueueTable()
    {
        $queues = Queue::with([
            'appointment.patient.user',
            'appointment.doctor.user',
            'appointment.doctor.specialization'
        ])
        ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
        ->whereDate('appointments.appointment_date', today())
        ->orderBy('queues.queue_number')
        ->get();

        return view('admin.queues.table_example', compact('queues'));
    }

    FITUR TAMBAHAN:
    - Responsive design dengan Bootstrap
    - Status antrian dengan badge warna-warni
    - Icon untuk setiap kolom
    - Empty state ketika tidak ada antrian
    - Legenda status di bagian bawah

    KUSTOMISASI:
    - Ubah warna badge status di $statusConfig
    - Sesuaikan icon dan layout sesuai kebutuhan
    - Tambahkan kolom lain jika diperlukan
    - Modifikasi styling CSS inline jika perlu

--}}
