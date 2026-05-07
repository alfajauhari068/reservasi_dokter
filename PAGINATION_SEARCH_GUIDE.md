# 🔍 **Pagination & Pencarian pada Tabel Antrian Harian**

## 🎯 **Fitur yang Ditambahkan**

### ✅ **Pagination**

- Navigasi halaman dengan link Previous/Next
- Pilihan jumlah data per halaman (5, 10, 25, 50)
- Informasi jumlah data yang ditampilkan
- Pertahankan parameter pencarian di semua halaman

### ✅ **Pencarian Sederhana**

- Cari berdasarkan nama pasien
- Cari berdasarkan nama dokter
- Cari berdasarkan kode booking
- Cari berdasarkan nomor antrian
- Reset pencarian dengan mudah

## 📁 **File yang Dimodifikasi**

### 1. **Controller Example**

📄 `app/Http/Controllers/Admin/QueueControllerExample.php`

- ✅ Method `dailyQueueTableWithSearch()` - Menggunakan Eloquent
- ✅ Method `dailyQueueTableOptimizedWithSearch()` - Menggunakan Query Builder
- ✅ Parameter `Request $request` untuk menangkap input search
- ✅ Pagination dengan `paginate($perPage)`
- ✅ Query appends untuk mempertahankan parameter

### 2. **View Template**

📄 `resources/views/admin/queues/table_example.blade.php`

- ✅ Form pencarian dengan input text dan select per_page
- ✅ Tombol Cari dan Reset
- ✅ Info hasil pencarian
- ✅ Pagination links Laravel
- ✅ Counter data yang ditampilkan

### 3. **Routes**

📄 `routes/web.php`

- ✅ Contoh route untuk method dengan search & pagination

## 🚀 **Cara Implementasi**

### **Langkah 1: Controller Method**

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    public function dailyQueueTableWithSearch(Request $request)
    {
        // Parameter dari request
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        // Query dengan eager loading
        $query = Queue::with([
            'appointment.patient.user',
            'appointment.doctor.user',
            'appointment.doctor.specialization'
        ])
        ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
        ->join('users as patient_users', 'patients.user_id', '=', 'patient_users.id')
        ->whereDate('appointments.appointment_date', today());

        // Filter pencarian
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('patient_users.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('doctor_users.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('appointments.booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhere('queues.queue_number', 'LIKE', '%' . $search . '%');
            });
        }

        // Pagination
        $queues = $query->orderBy('queues.queue_number')
                        ->paginate($perPage)
                        ->appends($request->query());

        return view('admin.queues.table_example', compact('queues', 'search', 'perPage'));
    }
}
```

### **Langkah 2: Route**

```php
// routes/web.php
Route::get('admin/queues/search', [QueueController::class, 'dailyQueueTableWithSearch'])
    ->name('admin.queues.search')
    ->middleware(['auth', 'role:admin']);
```

### **Langkah 3: View Form Pencarian**

```php
{{-- Form pencarian --}}
<form method="GET" action="{{ request()->url() }}" class="row g-3">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama pasien, dokter, atau kode booking..."
                   value="{{ $search ?? '' }}">
        </div>
    </div>
    <div class="col-md-3">
        <select name="per_page" class="form-select">
            <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
            <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 per halaman</option>
            <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 per halaman</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search me-1"></i>Cari
        </button>
    </div>
</form>
```

### **Langkah 4: Pagination Links**

```php
{{-- Pagination --}}
@if($queues->hasPages())
    <div class="card-footer bg-light border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $queues->firstItem() }}-{{ $queues->lastItem() }} dari {{ $queues->total() }} data
            </div>
            <div>
                {{ $queues->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endif
```

## 🔧 **Fitur Lengkap**

### **Pencarian**

- **Multi-field search**: Pasien, dokter, booking code, nomor antrian
- **Case-insensitive**: Pencarian tidak case-sensitive
- **Real-time feedback**: Tampilkan jumlah hasil pencarian
- **Easy reset**: Tombol reset untuk clear pencarian

### **Pagination**

- **Bootstrap styling**: Menggunakan pagination Bootstrap
- **Parameter persistence**: Pertahankan parameter search di semua halaman
- **Info counter**: Tampilkan "X-Y dari Z data"
- **Responsive**: Tampil baik di mobile dan desktop

### **UX/UI**

- **Loading states**: Form submit dengan loading indicator
- **Empty states**: Pesan khusus ketika tidak ada hasil
- **Accessibility**: Label dan ARIA attributes
- **Mobile-first**: Responsive design

## 📊 **Contoh URL**

```
/admin/queues/search
/admin/queues/search?search=john
/admin/queues/search?search=john&per_page=25
/admin/queues/search?page=2&search=john&per_page=25
```

## ⚡ **Performa**

### **Query Optimization**

- Menggunakan JOIN untuk mengurangi N+1 queries
- Eager loading untuk relationships
- Indexed search fields untuk performa optimal
- Pagination untuk membatasi data per request

### **Database Indexing**

Pastikan field yang dicari memiliki index:

```sql
CREATE INDEX idx_patient_name ON users(name);
CREATE INDEX idx_doctor_name ON users(name);
CREATE INDEX idx_booking_code ON appointments(booking_code);
CREATE INDEX idx_queue_number ON queues(queue_number);
```

## 🎯 **Keunggulan Implementasi**

1. **🔍 Smart Search** - Pencarian di multiple fields
2. **📄 Efficient Pagination** - Load data per halaman
3. **💾 Parameter Persistence** - Pertahankan filter di semua halaman
4. **📱 Mobile Friendly** - Responsive di semua device
5. **⚡ High Performance** - Query optimal dan indexed
6. **🛡️ Secure** - Input sanitization dan validation
7. **🎨 Clean UI** - Design yang intuitif dan modern

## 🚀 **Testing**

### **Test Pencarian**

```bash
# Test pencarian nama pasien
curl "http://localhost:8000/admin/queues/search?search=john"

# Test pagination
curl "http://localhost:8000/admin/queues/search?page=2&per_page=10"

# Test kombinasi
curl "http://localhost:8000/admin/queues/search?search=john&page=1&per_page=5"
```

### **Test Performa**

- Monitor query execution time
- Check database query count
- Test dengan data besar (1000+ records)

## 📝 **Tips Penggunaan**

1. **Gunakan indexing** pada field yang dicari
2. **Limit per_page** maksimal 100 untuk performa
3. **Validate input** search untuk keamanan
4. **Cache results** jika data jarang berubah
5. **Monitor queries** dengan Laravel Debugbar

Implementasi ini memberikan pengalaman user yang excellent dengan performa yang optimal! 🚀
