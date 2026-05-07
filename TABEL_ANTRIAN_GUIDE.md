# 📋 Panduan Lengkap Tabel Antrian Harian

## 🎯 **Tujuan**

Membuat tampilan tabel antrian harian yang rapi dan mudah dibaca dengan kolom:

- ✅ No Antrian
- ✅ Nama Pasien
- ✅ Dokter
- ✅ Jam Kunjungan/Jadwal
- ✅ Status Antrian

## 📁 **File yang Dibuat**

### 1. **View Template**

📄 `resources/views/admin/queues/table_example.blade.php`

- Markup Blade lengkap untuk tabel antrian
- Responsive design dengan Bootstrap 5
- Status antrian dengan badge warna-warni

### 2. **Controller Example**

📄 `app/Http/Controllers/Admin/QueueControllerExample.php`

- Contoh method controller untuk mengambil data
- Query dengan eager loading dan query builder
- Dokumentasi lengkap cara penggunaan

### 3. **Dokumentasi**

📄 `resources/views/admin/queues/README.md`

- Panduan lengkap penggunaan
- Struktur data yang diperlukan
- Contoh kustomisasi

## 🚀 **Cara Penggunaan**

### **Langkah 1: Copy Template**

```php
{{-- Copy dari table_example.blade.php ke view Anda --}}
@include('admin.queues.table_example')
```

### **Langkah 2: Setup Controller**

```php
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

    return view('your.view', compact('queues'));
}
```

### **Langkah 3: Setup Route** (Opsional)

```php
Route::get('/admin/queues/table', [QueueController::class, 'dailyQueueTable'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.queues.table');
```

## 📊 **Struktur Data**

### **Data yang Diperlukan:**

```php
$queues = [
    [
        'queue_number' => 1,
        'appointment' => [
            'patient' => [
                'user' => ['name' => 'John Doe'],
                'patient_id' => 'P001'
            ],
            'doctor' => [
                'user' => ['name' => 'Dr. Smith'],
                'specialization' => ['name' => 'Umum']
            ],
            'appointment_time' => '2024-01-01 09:00:00',
            'appointment_date' => '2024-01-01'
        ],
        'queue_status' => 'waiting' // waiting, called, served, skipped
    ]
];
```

## 🎨 **Fitur Design**

### **Status Antrian:**

- 🟡 **Menunggu** - Badge kuning
- 🔵 **Dipanggil** - Badge biru
- 🟢 **Selesai** - Badge hijau
- 🔴 **Dilewati** - Badge merah

### **Responsive:**

- ✅ Mobile-friendly
- ✅ Table responsive
- ✅ Icon yang informatif

### **UX/UI:**

- ✅ Empty state untuk data kosong
- ✅ Legenda status
- ✅ Hover effects
- ✅ Consistent spacing

## 🔧 **Kustomisasi**

### **Mengubah Warna Status:**

```php
$statusConfig = [
    'waiting' => ['class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock', 'text' => 'Menunggu'],
    'called' => ['class' => 'bg-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Dipanggil'],
    // ... sesuaikan warna dan icon
];
```

### **Menambah Kolom Baru:**

```php
<th class="border-0 fw-semibold">Kolom Baru</th>
{{-- Di dalam loop --}}
<td>{{ $queue->new_field }}</td>
```

## 📝 **Contoh Implementasi Lengkap**

### **Controller Method:**

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function dailyQueueTable()
    {
        $queues = Queue::with([
            'appointment.patient.user',
            'appointment.doctor.user',
            'appointment.doctor.specialization'
        ])
        ->whereHas('appointment', function($q) {
            $q->whereDate('appointment_date', today());
        })
        ->orderBy('queue_number')
        ->get();

        return view('admin.queues.table_example', compact('queues'));
    }
}
```

### **Route:**

```php
Route::get('/admin/daily-queue', [QueueController::class, 'dailyQueueTable'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.daily-queue');
```

## ✅ **Keunggulan Template Ini**

1. **📱 Responsive** - Tampil sempurna di semua device
2. **🎨 Modern UI** - Design yang clean dan professional
3. **⚡ Performa** - Query optimal dengan eager loading
4. **🔧 Mudah Customize** - Bisa dimodifikasi sesuai kebutuhan
5. **📖 Well Documented** - Dokumentasi lengkap dan jelas
6. **🛡️ Secure** - Middleware authentication dan role check

## 🎯 **Ready to Use**

Template ini siap digunakan langsung tanpa modifikasi. Cukup:

1. Copy file `table_example.blade.php`
2. Buat method controller
3. Setup route (opsional)
4. Akses halaman

Template ini mengikuti best practices Laravel dan Bootstrap, sehingga mudah diintegrasikan dengan project existing Anda! 🚀
