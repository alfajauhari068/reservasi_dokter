<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservasi Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f7fb; padding:40px;">

    <div style="
        max-width:600px;
        margin:auto;
        background:white;
        border-radius:16px;
        padding:32px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
    ">

        <h2 style="color:#0f172a;">
            Reservasi Berhasil
        </h2>

        <p>Halo {{ $appointment->patient->name }},</p>

        <p>
            Reservasi Anda telah berhasil dibuat dan disetujui secara otomatis oleh sistem.
        </p>

        <h3 style="margin-top:30px;">
            Detail Reservasi
        </h3>

        <table width="100%" cellpadding="8" style="border-collapse: collapse;">
            <tr>
                <td><strong>Nama Dokter</strong></td>
                <td>{{ $appointment->doctor->name }}</td>
            </tr>

            <tr>
                <td><strong>Spesialisasi</strong></td>
                <td>{{ $appointment->doctor->specialization }}</td>
            </tr>

            <tr>
                <td><strong>Tanggal Reservasi</strong></td>
                <td>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}
                </td>
            </tr>

            <tr>
                <td><strong>Kode Booking</strong></td>
                <td>{{ $appointment->booking_code }}</td>
            </tr>

            <tr>
                <td><strong>Nomor Antrian</strong></td>
                <td>{{ $appointment->queue_number }}</td>
            </tr>
        </table>

        <p style="margin-top:30px;">
            Silakan cek kembali halaman profil atau riwayat reservasi Anda untuk melihat detail lebih lanjut.
        </p>

        <p>
            Terima kasih telah menggunakan layanan reservasi klinik kami.
        </p>

        <br>

        <p>
            Salam,<br>
            <strong>Tim Reservasi Klinik</strong>
        </p>

    </div>

</body>
</html>
