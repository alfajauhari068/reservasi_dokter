<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Reservasi</title>
</head>
<body>
    <h1>Reservasi Berhasil</h1>
    <p>Halo {{ $appointment->patient->user->name }},</p>

    <p>Reservasi Anda telah berhasil dibuat dan disetujui secara otomatis oleh sistem.</p>

    <p><strong>Detail Reservasi:</strong></p>
    <ul>
        <li>Nama Dokter: {{ $appointment->doctor->user->name }}</li>
        <li>Spesialisasi: {{ $appointment->doctor->specialization?->name ?? 'Tidak tersedia' }}</li>
        <li>Tanggal Reservasi: {{ $appointment->appointment_date->format('d M Y') }}</li>
        <li>Kode Booking: {{ $appointment->booking_code }}</li>
        <li>Nomor Antrian: {{ $appointment->queue_number }}</li>
    </ul>

    <p>Silakan cek kembali halaman profil atau riwayat reservasi Anda untuk melihat detail lebih lanjut.</p>

    <p>Terima kasih telah menggunakan layanan reservasi klinik kami.</p>

    <p>Salam,<br>
    Tim Reservasi Klinik</p>
</body>
</html>
