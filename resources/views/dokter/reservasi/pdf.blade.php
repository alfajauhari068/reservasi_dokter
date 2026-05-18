<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - {{ $appointment->patient->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .info-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .diagnosis-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Pasien</h1>
        <p>Kode Booking: {{ $appointment->booking_code }}</p>
    </div>

    <div class="section">
        <h2>Informasi Pasien</h2>
        <table class="info-table">
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $appointment->patient->full_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $appointment->patient->user ? $appointment->patient->user->email : 'Tidak ada' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('d/m/Y') : 'Tidak ada' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $appointment->patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td>{{ $appointment->patient->phone_number ?: 'Tidak ada' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $appointment->patient->address ?: 'Tidak ada' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Informasi Reservasi</h2>
        <table class="info-table">
            <tr>
                <th>Tanggal Reservasi</th>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>Dokter</th>
                <td>{{ $appointment->doctor->user->name }}</td>
            </tr>
            <tr>
                <th>Spesialisasi</th>
                <td>{{ $appointment->doctor->specialization->name }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($appointment->status) }}</td>
            </tr>
        </table>
    </div>

    @if($appointment->medicalRecord)
    <div class="section">
        <h2>Rekam Medis</h2>
        <div class="diagnosis-section">
            <h3>Diagnosis</h3>
            <p>{{ $appointment->medicalRecord->diagnosis }}</p>

            @if($appointment->medicalRecord->prescription)
            <h3>Resep</h3>
            <p>{{ $appointment->medicalRecord->prescription }}</p>
            @endif

            @if($appointment->medicalRecord->doctor_notes)
            <h3>Catatan Dokter</h3>
            <p>{{ $appointment->medicalRecord->doctor_notes }}</p>
            @endif
        </div>
    </div>
    @endif

    <div class="section">
        <p><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>