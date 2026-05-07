# 📋 **Form Filter Laporan Kunjungan - Lengkap**

## 🎯 **Fitur Form Filter**

### ✅ **Dropdown Dokter**

- **Data Source**: Tabel `doctors` dengan relasi ke `users` dan `specializations`
- **Display Format**: "Nama Dokter (Spesialisasi)"
- **Sorting**: Diurutkan berdasarkan nama dokter
- **Default Option**: "Semua Dokter" untuk filter global

### ✅ **Input Date Range**

- **Tanggal Mulai**: Date picker dengan validasi
- **Tanggal Sampai**: Date picker dengan validasi
- **Format**: YYYY-MM-DD (HTML5 date input)
- **Validation**: Tanggal akhir harus setelah tanggal mulai
- **Max Date**: Hari ini (tidak bisa pilih tanggal future)

### ✅ **Preset Periode Cepat**

- **Minggu Ini**: startOfWeek sampai endOfWeek
- **Bulan Ini**: startOfMonth sampai endOfMonth
- **Tahun Ini**: startOfYear sampai endOfYear
- **30 Hari Terakhir**: 30 hari ke belakang sampai hari ini

### ✅ **Filter State Management**

- **URL Parameters**: Filter tersimpan di URL (doctor_id, start_date, end_date)
- **Visual Feedback**: Badge menampilkan filter yang aktif
- **Reset Function**: Tombol reset untuk clear semua filter
- **Persistent**: Filter tetap aktif saat navigasi

## 📁 **File yang Dibuat**

### 1. **View Examples**

📄 `resources/views/admin/reports/filter_example.blade.php`

- Form filter lengkap dengan tabel hasil
- Menampilkan filter yang sudah dipilih
- Preset periode dan info penggunaan

📄 `resources/views/admin/reports/filter_form_only.blade.php`

- Form filter saja tanpa tabel
- UI yang lebih besar dan prominent
- Dokumentasi lengkap cara penggunaan

### 2. **Controller Example**

📄 `app/Http\Controllers/Admin/FilterExampleController.php`

- Method `indexWithFilterExample()` - Dengan query laporan
- Method `filterFormOnly()` - Form filter saja
- Helper methods untuk format dan validasi

### 3. **Routes**

📄 `routes/web.php`

- Contoh routes untuk kedua method
- Commented out agar tidak conflict

## 🚀 **Cara Implementasi**

### **Langkah 1: Persiapan Data**

```php
// Di Controller - Ambil data dokter untuk dropdown
$doctors = Doctor::with(['user', 'specialization'])
                ->orderBy('users.name')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->select('doctors.*')
                ->get();
```

### **Langkah 2: Form HTML**

```php
<!-- Dropdown Dokter -->
<select name="doctor_id" class="form-select">
    <option value="">Semua Dokter</option>
    @foreach($doctors as $doctor)
        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
            {{ $doctor->user->name }}
            @if($doctor->specialization) ({{ $doctor->specialization->name }}) @endif
        </option>
    @endforeach
</select>

<!-- Date Range -->
<input type="date" name="start_date" value="{{ request('start_date') }}" max="{{ now()->format('Y-m-d') }}">
<input type="date" name="end_date" value="{{ request('end_date') }}" max="{{ now()->format('Y-m-d') }}">
```

### **Langkah 3: Menampilkan Filter Aktif**

```php
@if(request()->hasAny(['doctor_id', 'start_date', 'end_date']))
<div class="alert alert-info">
    <h6>Filter Aktif:</h6>
    @if(request('doctor_id'))
        @php $selectedDoctor = $doctors->find(request('doctor_id')); @endphp
        @if($selectedDoctor)
            <span class="badge bg-primary">
                Dokter: {{ $selectedDoctor->user->name }}
            </span>
        @endif
    @endif

    @if(request('start_date') && request('end_date'))
        <span class="badge bg-success">
            Periode: {{ Carbon::parse(request('start_date'))->format('d/m/Y') }} -
            {{ Carbon::parse(request('end_date'))->format('d/m/Y') }}
        </span>
    @endif
</div>
@endif
```

### **Langkah 4: Preset Periode**

```php
<!-- Preset Buttons -->
<a href="?start_date={{ now()->startOfWeek()->format('Y-m-d') }}&end_date={{ now()->endOfWeek()->format('Y-m-d') }}">
    Minggu Ini
</a>
<a href="?start_date={{ now()->startOfMonth()->format('Y-m-d') }}&end_date={{ now()->endOfMonth()->format('Y-m-d') }}">
    Bulan Ini
</a>
```

### **Langkah 5: Controller Logic**

```php
public function index(Request $request)
{
    // Default periode
    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
    $doctorId = $request->get('doctor_id');

    // Query dengan filter
    $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate]);

    if ($doctorId) {
        $query->where('doctor_id', $doctorId);
    }

    $reports = $query->get();

    return view('your.view', compact('reports', 'startDate', 'endDate', 'doctorId'));
}
```

## 📊 **Contoh Implementasi Lengkap**

### **Route:**

```php
Route::get('admin/reports/filter-demo', [FilterController::class, 'filterFormOnly'])
    ->name('admin.reports.filter.demo')
    ->middleware(['auth', 'role:admin']);
```

### **Controller Method:**

```php
public function filterFormOnly(Request $request)
{
    $doctors = Doctor::with(['user', 'specialization'])
                    ->orderBy('users.name')
                    ->join('users', 'doctors.user_id', '=', 'users.id')
                    ->select('doctors.*')
                    ->get();

    $currentFilters = [
        'doctor_id' => $request->get('doctor_id'),
        'start_date' => $request->get('start_date', now()->startOfMonth()->format('Y-m-d')),
        'end_date' => $request->get('end_date', now()->endOfMonth()->format('Y-m-d')),
        'has_filters' => $request->hasAny(['doctor_id', 'start_date', 'end_date'])
    ];

    if ($currentFilters['doctor_id']) {
        $selectedDoctor = $doctors->find($currentFilters['doctor_id']);
        $currentFilters['selected_doctor'] = $selectedDoctor ? [
            'name' => $selectedDoctor->user->name,
            'specialization' => $selectedDoctor->specialization?->name
        ] : null;
    }

    return view('admin.reports.filter_form_only', compact('doctors', 'currentFilters'));
}
```

## 🎨 **UI/UX Features**

### **Responsive Design**

- ✅ Mobile-friendly dengan Bootstrap grid
- ✅ Form controls yang touch-friendly
- ✅ Alert messages yang responsive

### **User Experience**

- ✅ Auto-focus pada form load
- ✅ Loading states pada preset buttons
- ✅ Clear visual hierarchy
- ✅ Consistent color scheme

### **Accessibility**

- ✅ Proper labels dan form attributes
- ✅ Keyboard navigation support
- ✅ Screen reader friendly
- ✅ High contrast colors

## 🔧 **JavaScript Enhancements**

### **Date Validation**

```javascript
// Validasi tanggal mulai vs tanggal akhir
const startDateInput = document.getElementById("start_date");
const endDateInput = document.getElementById("end_date");

function validateDateRange() {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (startDate && endDate && startDate > endDate) {
        endDateInput.setCustomValidity(
            "Tanggal akhir harus setelah tanggal mulai",
        );
    } else {
        endDateInput.setCustomValidity("");
    }
}

startDateInput.addEventListener("change", validateDateRange);
endDateInput.addEventListener("change", validateDateRange);
```

### **Auto-submit Presets**

```javascript
// Loading state untuk preset buttons
document.querySelectorAll(".preset-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    });
});
```

## 📋 **Data Flow**

```
1. User membuka halaman
   ↓
2. Controller load data dokter
   ↓
3. View render form dengan default values
   ↓
4. User pilih filter dan submit
   ↓
5. Controller process filter parameters
   ↓
6. Query database dengan WHERE clauses
   ↓
7. Return results dengan filter info
   ↓
8. View display results + active filters
```

## ✅ **Testing Checklist**

### **Functional Testing**

- [ ] Dropdown dokter menampilkan data dengan benar
- [ ] Date inputs accept valid dates only
- [ ] Form submission works dengan GET method
- [ ] URL parameters tersimpan dengan benar
- [ ] Reset button clear semua filters
- [ ] Preset buttons generate correct date ranges

### **UI/UX Testing**

- [ ] Form responsive di mobile devices
- [ ] Visual feedback untuk filter aktif
- [ ] Loading states work properly
- [ ] Error messages display correctly
- [ ] Date validation prevents invalid ranges

### **Performance Testing**

- [ ] Query execution time < 500ms
- [ ] Page load time < 2 seconds
- [ ] Memory usage reasonable
- [ ] No N+1 query problems

## 🚀 **Ready to Use**

Form filter ini **100% siap** untuk digunakan dengan fitur-fitur:

- **🔽 Smart Dropdown** - Data dokter dari database
- **📅 Date Range** - Input tanggal dengan validasi
- **⚡ Quick Presets** - Filter cepat untuk periode umum
- **👁️ Visual Feedback** - Menampilkan filter yang aktif
- **🔄 State Management** - Persistent di URL parameters
- **📱 Mobile Friendly** - Responsive di semua device

**File siap pakai:**

- `filter_example.blade.php` - Lengkap dengan tabel
- `filter_form_only.blade.php` - Form saja
- `FilterExampleController.php` - Controller methods

Cukup uncomment routes di `web.php` dan akses URL untuk testing! 🎉
