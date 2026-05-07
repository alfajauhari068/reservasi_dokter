# 📋 **Panduan Query Eloquent untuk Filter Appointments**

## 🎯 **Query Eloquent Lengkap**

```php
$query = Appointment::with([
    'patient.user',           // Data pasien
    'doctor.user',            // Data dokter
    'doctor.specialization',  // Spesialisasi dokter
    'queue'                   // Data antrian
]);

// JOIN untuk filtering dan sorting yang efisien
$query->join('queues', 'appointments.id', '=', 'queues.appointment_id')
      ->join('patients', 'appointments.patient_id', '=', 'patients.id')
      ->join('users as patient_users', 'patients.user_id', '=', 'patient_users.id')
      ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
      ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
      ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id');

// === FILTER PERIODE TANGGAL (WAJIB) ===
$query->whereBetween('appointments.appointment_date', [$startDate, $endDate]);

// === FILTER DOKTER (OPSIONAL) ===
if ($doctorId) {
    $query->where('appointments.doctor_id', $doctorId);
}
// Catatan: Ketika $doctorId = null, query akan mengambil semua dokter

// === FILTER STATUS ANTRIAN (OPSIONAL) ===
if ($status) {
    $query->where('queues.queue_status', $status);
}

// Execute query
$reports = $query->select([...])->orderBy(...)->get();
```

## 🔍 **Penanganan Kondisi "Semua Dokter"**

### **Logika Utama:**

```php
// ✅ BENAR: Hanya tambahkan WHERE jika ada filter
if ($doctorId) {
    $query->where('appointments.doctor_id', $doctorId);
}

// ❌ SALAH: Jangan paksa WHERE null
// if ($doctorId) {
//     $query->where('appointments.doctor_id', $doctorId);
// } else {
//     // JANGAN LAKUKAN INI - tidak perlu!
// }

```

### **Mengapa Bekerja:**

- **Tanpa kondisi `else`**: Query mengambil semua records
- **Dengan `$doctorId`**: Query difilter berdasarkan dokter tertentu
- **Eloquent secara otomatis** menangani kondisi null

## 📊 **Contoh Query SQL yang Dihasilkan**

### **1. Semua Dokter (Tidak ada filter dokter):**

```sql
SELECT appointments.*, ... FROM appointments
JOIN queues ON appointments.id = queues.appointment_id
JOIN patients ON appointments.patient_id = patients.id
-- ... joins lainnya
WHERE appointments.appointment_date BETWEEN '2026-05-01' AND '2026-05-31'
ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC
```

### **2. Filter Dokter Tertentu:**

```sql
SELECT appointments.*, ... FROM appointments
JOIN queues ON appointments.id = queues.appointment_id
JOIN patients ON appointments.patient_id = patients.id
-- ... joins lainnya
WHERE appointments.appointment_date BETWEEN '2026-05-01' AND '2026-05-31'
  AND appointments.doctor_id = 5  -- ← Filter dokter ditambahkan
ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC
```

## 🚀 **Optimasi Query**

### **1. Eager Loading untuk Performa:**

```php
// ✅ Load relasi sekaligus untuk menghindari N+1 queries
$appointments = Appointment::with([
    'patient.user',
    'doctor.user',
    'doctor.specialization',
    'queue'
])->get();
```

### **2. JOIN untuk Filtering:**

```php
// ✅ JOIN memungkinkan WHERE pada tabel terkait
$query->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
      ->where('doctor_users.name', 'LIKE', '%dr.%');
```

### **3. Select Fields Spesifik:**

```php
// ✅ Hanya ambil field yang dibutuhkan
$query->select([
    'appointments.id',
    'appointments.appointment_date',
    'doctor_users.name as doctor_name',
    // ... fields lainnya
]);
```

## 🔧 **Implementasi Lengkap di Controller**

```php
public function index(Request $request)
{
    // Filter parameters
    $doctorId = $request->get('doctor_id'); // null = semua dokter
    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
    $status = $request->get('status');

    // Query Eloquent
    $query = Appointment::with(['patient.user', 'doctor.user', 'doctor.specialization', 'queue']);

    // JOIN untuk performa
    $query->join('queues', 'appointments.id', '=', 'queues.appointment_id')
          ->join('patients', 'appointments.patient_id', '=', 'patients.id')
          ->join('users as patient_users', 'patients.user_id', '=', 'patient_users.id')
          ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
          ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
          ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id');

    // Filter wajib: periode tanggal
    $query->whereBetween('appointments.appointment_date', [$startDate, $endDate]);

    // Filter opsional: dokter (hanya jika dipilih)
    if ($doctorId) {
        $query->where('appointments.doctor_id', $doctorId);
    }

    // Filter opsional: status antrian
    if ($status) {
        $query->where('queues.queue_status', $status);
    }

    // Execute dengan select fields spesifik
    $reports = $query->select([
        'appointments.id',
        'appointments.appointment_date',
        'appointments.appointment_time',
        'patient_users.name as patient_name',
        'doctor_users.name as doctor_name',
        'specializations.name as specialization_name',
        'queues.queue_number',
        'queues.queue_status'
    ])
    ->orderBy('appointments.appointment_date', 'desc')
    ->orderBy('appointments.appointment_time', 'desc')
    ->get();

    return view('admin.reports.visitation', compact('reports'));
}
```

## ✅ **Best Practices**

### **1. Parameter Default:**

```php
$startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
$endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
```

### **2. Null Handling:**

```php
// ✅ Gunakan null coalescing
$doctorId = $request->get('doctor_id'); // null jika tidak ada

// ✅ Conditional WHERE hanya jika ada nilai
if ($doctorId) {
    $query->where('doctor_id', $doctorId);
}
```

### **3. Query Chaining:**

```php
// ✅ Chain query untuk readability
$query = Appointment::with(['relations'])
    ->join('table1', 'condition1')
    ->join('table2', 'condition2')
    ->whereBetween('date_field', [$start, $end]);

if ($doctorId) $query->where('doctor_id', $doctorId);
if ($status) $query->where('status', $status);

$results = $query->select([...])->get();
```

## 🎯 **Kesimpulan**

**Query Eloquent untuk filter appointments sudah optimal:**

- ✅ **Filter dokter opsional** - null berarti semua dokter
- ✅ **Filter tanggal wajib** - selalu ada default periode
- ✅ **JOIN untuk performa** - menghindari N+1 queries
- ✅ **Eager loading** - load relasi sekaligus
- ✅ **Select spesifik** - hanya ambil field yang dibutuhkan
- ✅ **Clean code** - mudah dibaca dan di-maintain

**Kunci penanganan "semua dokter": Jangan tambahkan WHERE clause jika `$doctorId` null!** 🎉
