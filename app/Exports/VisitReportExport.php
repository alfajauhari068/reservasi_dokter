<?php

namespace App\Exports;

use App\Services\VisitReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Request $request;
    protected VisitReportService $service;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->service = new VisitReportService();
    }

    public function collection(): Collection
    {
        return $this->service->buildReportQuery($this->request)
            ->select([
                'appointments.booking_code',
                'patient_users.name as patient_name',
                'doctor_users.name as doctor_name',
                'specializations.name as specialization_name',
                'appointments.appointment_date',
                'schedules.start_time as appointment_time',
                'queues.queue_status'
            ])
            ->orderBy('appointments.appointment_date', 'desc')
            ->orderBy('schedules.start_time', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Booking',
            'Nama Pasien',
            'Nama Dokter',
            'Spesialisasi',
            'Tanggal Kunjungan',
            'Jam Kunjungan',
            'Status'
        ];
    }

    public function map($row): array
    {
        return [
            $row->booking_code,
            $row->patient_name,
            $row->doctor_name,
            $row->specialization_name,
            optional($row->appointment_date) ? \Carbon\Carbon::parse($row->appointment_date)->format('d/m/Y') : null,
            optional($row->appointment_time) ? \Carbon\Carbon::parse($row->appointment_time)->format('H:i') : null,
            ucfirst($row->queue_status),
        ];
    }
}
