<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Pemeriksaan - {{ $appointment->booking_code }}</title>
    <style>
        body { font-family: sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 1.5rem; }
        .header h1 { margin: 0; font-size: 1.4rem; }
        .header p { margin: 0.25rem 0; color: #555; }
        .section { margin-bottom: 1rem; }
        .section-title { font-weight: bold; margin-bottom: 0.5rem; border-bottom: 1px solid #ddd; padding-bottom: 0.25rem; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 0.45rem 0.5rem; vertical-align: top; }
        .summary-table th { width: 35%; text-align: left; color: #555; }
        .summary-table td { width: 65%; }
        .note { font-size: 0.9rem; color: #555; }
        .footer { margin-top: 2rem; font-size: 0.85rem; color: #666; }
        .badge { display: inline-block; padding: 0.2rem 0.45rem; border-radius: 0.35rem; color: #fff; font-size: 0.85rem; }
        .badge-info { background: #0dcaf0; }
        .badge-warning { background: #ffc107; }
        .badge-success { background: #198754; }
        .badge-danger { background: #dc3545; }
        .badge-secondary { background: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Riwayat Pemeriksaan</h1>
        <p>Klinik Reservasi</p>
        <p>{{ $appointment->booking_code }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informasi Reservasi</div>
        <table class="summary-table">
            <tr>
                <th>Kode Booking</th>
                <td>{{ $appointment->booking_code }}</td>
            </tr>
            <tr>
                <th>Tanggal Pemeriksaan</th>
                <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Status Reservasi</th>
                <td>{{ ucwords(str_replace('_', ' ', $appointment->status)) }}</td>
            </tr>
            <tr>
                <th>Status Approval</th>
                <td>{{ ucwords(str_replace('_', ' ', $appointment->approval_status)) }}</td>
            </tr>
            <tr>
                <th>Nomor Antrian</th>
                <td>{{ $appointment->queue_number ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Data Pasien</div>
        <table class="summary-table">
            <tr>
                <th>Nama</th>
                <td>{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $appointment->patient->user->email }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $appointment->patient->phone_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $appointment->patient->address ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Data Dokter</div>
        <table class="summary-table">
            <tr>
                <th>Nama Dokter</th>
                <td>{{ $appointment->doctor->user->name }}</td>
            </tr>
            <tr>
                <th>Spesialisasi</th>
                <td>{{ optional($appointment->doctor->specialization)->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jam</th>
                <td>{{ optional($appointment->schedule)->start_time ? substr($appointment->schedule->start_time, 0, 5) : '-' }} - {{ optional($appointment->schedule)->end_time ? substr($appointment->schedule->end_time, 0, 5) : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Keluhan Pasien</div>
        <p>{{ $appointment->complaint ?? '-' }}</p>
    </div>

    <div class="section">
        <div class="section-title">Hasil Pemeriksaan</div>
        <p><strong>Diagnosis:</strong><br>{{ $appointment->medicalRecord->diagnosis ?? '-' }}</p>
        <p><strong>Resep:</strong><br>{{ $appointment->medicalRecord->prescription ?? '-' }}</p>
        <p><strong>Catatan Dokter:</strong><br>{{ $appointment->medicalRecord->doctor_notes ?? '-' }}</p>
        <p class="note">Dicetak pada {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan dari sistem reservasi klinik dan dapat digunakan sebagai bukti riwayat pemeriksaan.</p>
    </div>
</body>
</html>
