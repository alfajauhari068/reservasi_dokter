<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .page-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-header h1 {
            margin: 0;
            font-size: 18px;
        }
        .page-header .meta {
            margin-top: 6px;
            font-size: 11px;
            color: #555;
        }
        .summary {
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .summary .box {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f8f8f8;
        }
        .summary .box strong {
            display: block;
            font-size: 20px;
            margin-bottom: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
        }
        .status-done { background: #28a745; }
        .status-cancelled { background: #dc3545; }
        .status-waiting { background: #ffc107; color: #212529; }
        .status-called { background: #0d6efd; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Laporan Kunjungan</h1>
        <div class="meta">
            Periode: {{ \Carbon\Carbon::parse($filter['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filter['end_date'])->format('d M Y') }}
            @if($selectedDoctor)
                | Dokter: {{ $selectedDoctor->user->name }}{{ $selectedDoctor->specialization ? ' (' . $selectedDoctor->specialization->name . ')' : '' }}
            @else
                | Dokter: Semua
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Pasien</th>
                <th style="width: 25%;">Dokter</th>
                <th style="width: 20%;">Tanggal Kunjungan</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
                @php
                    $status = $report->queue_status ?? ($report->queue->queue_status ?? 'unknown');
                    $badgeClass = match($status) {
                        'done' => 'status-done',
                        'cancelled' => 'status-cancelled',
                        'waiting' => 'status-waiting',
                        'called' => 'status-called',
                        default => 'status-waiting'
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->patient_name ?? $report->patient->user->name ?? '-' }}</td>
                    <td>{{ $report->doctor_name ?? $report->doctor->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->appointment_date)->format('d M Y') }}<br><small>{{ \Carbon\Carbon::parse($report->appointment_time)->format('H:i') }}</small></td>
                    <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 20px; color: #777;">Tidak ada data kunjungan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
