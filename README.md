# Sistem Reservasi Dokter & Manajemen Klinik

Aplikasi web berbasis **Laravel 10** untuk mengelola proses reservasi pasien, data dokter, jadwal praktik, nomor antrian, pemeriksaan, rekam medis, notifikasi, dan laporan kunjungan dalam satu sistem.

Project ini menggunakan pendekatan **role-based access** dengan tiga peran utama: **Admin, Dokter, dan Pasien**. Masing-masing peran memiliki dashboard dan alur kerja yang berbeda.

> **Status:** Active development / academic project  
> **Framework:** Laravel 10  
> **PHP:** ^8.1  
> **Database:** MySQL  
> **Frontend:** Blade + Vite  
> **Branch utama:** `main`

---

## Daftar Isi

- [Gambaran Sistem](#gambaran-sistem)
- [Tujuan](#tujuan)
- [Fitur](#fitur)
- [Peran Pengguna](#peran-pengguna)
- [Alur Bisnis](#alur-bisnis)
- [Arsitektur](#arsitektur)
- [Teknologi](#teknologi)
- [Struktur Project](#struktur-project)
- [Model Data](#model-data)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Database dan Seeder](#database-dan-seeder)
- [Akun Development](#akun-development)
- [Routing](#routing)
- [Notifikasi](#notifikasi)
- [Laporan dan Export](#laporan-dan-export)
- [Testing](#testing)
- [Perintah Pengembangan](#perintah-pengembangan)
- [Catatan Keamanan](#catatan-keamanan)
- [Catatan Audit Repository](#catatan-audit-repository)
- [Lisensi](#lisensi)

---

## Gambaran Sistem

Sistem dirancang untuk memindahkan proses pelayanan klinik dari pengelolaan reservasi dan antrian secara manual menjadi alur digital yang terintegrasi.

Secara umum:

```text
                    SISTEM RESERVASI DOKTER
                              │
          ┌───────────────────┼───────────────────┐
          │                   │                   │
        ADMIN              DOKTER              PASIEN
          │                   │                   │
          ▼                   ▼                   ▼
   Kelola dokter       Kelola pemeriksaan    Registrasi
   Kelola jadwal       Riwayat pasien        Profil
   Kelola reservasi    Diagnosis              Reservasi
   Kelola antrian      Resep                  Riwayat
   Laporan             Catatan dokter         Detail booking
          │                   │                   │
          └───────────────────┼───────────────────┘
                              ▼
                       DATABASE KLINIK
                              │
               ┌──────────────┼──────────────┐
               ▼              ▼              ▼
          Appointment       Queue       Medical Record
```

Model utama yang ditemukan pada repository meliputi `User`, `Doctor`, `Patient`, `Specialization`, `Schedule`, `Appointment`, `Queue`, `MedicalRecord`, dan `Notification`. fileciteturn54file0

---

## Tujuan

Project ini bertujuan menyediakan sistem terintegrasi untuk:

- registrasi pasien;
- autentikasi pengguna berdasarkan role;
- pengelolaan dokter dan spesialisasi;
- pengelolaan jadwal praktik;
- reservasi konsultasi;
- pembuatan nomor antrian;
- pemantauan status antrian;
- pengelolaan pemeriksaan oleh dokter;
- penyimpanan rekam medis;
- notifikasi antar peran;
- pencetakan detail pemeriksaan ke PDF;
- laporan kunjungan dengan filter;
- export laporan ke PDF dan Excel.

---

## Fitur

### 1. Autentikasi dan Role

Tersedia tiga role utama:

| Role | Fungsi utama |
|---|---|
| `admin` | Mengelola operasional sistem klinik |
| `dokter` | Menangani reservasi dan pemeriksaan pasien |
| `pasien` | Registrasi, profil, reservasi, dan riwayat |

Login melakukan validasi credential, regenerasi session setelah berhasil, kemudian mengarahkan pengguna berdasarkan role. fileciteturn69file0

Registrasi publik digunakan untuk membuat akun pasien sekaligus profil pasien dalam satu transaction database. fileciteturn70file0

### 2. Manajemen Dokter

Admin dapat:

- melihat daftar dokter;
- menambahkan dokter;
- membuat akun login dokter;
- menentukan spesialisasi;
- mengelola nomor izin;
- mengelola pengalaman dan biaya konsultasi;
- mengunggah foto dokter;
- mengubah status ketersediaan;
- mengelola jadwal praktik.

Pembuatan dokter sekaligus membuat user dengan role `dokter`. Upload foto disimpan pada disk `public`. fileciteturn61file0

### 3. Manajemen Jadwal

Jadwal dokter memiliki:

- hari praktik;
- jam mulai;
- jam selesai;
- kuota.

Sistem memvalidasi waktu dan melakukan pengecekan overlap jadwal sebelum jadwal disimpan. fileciteturn61file0

### 4. Reservasi Pasien

Pasien dapat:

1. memilih dokter;
2. memilih jadwal;
3. memilih tanggal yang sesuai dengan hari praktik;
4. memasukkan keluhan;
5. membuat reservasi;
6. mendapatkan booking code dan nomor antrian;
7. melihat detail dan riwayat reservasi.

Controller reservasi memvalidasi dokter, jadwal, tanggal, dan profil pasien sebelum membuat appointment. Proses pembuatan appointment dan queue dilakukan dalam database transaction. fileciteturn62file0

### 5. Nomor Antrian

Sistem memiliki status:

```text
waiting → called → served
                  ↘ skipped
```

Nomor antrian dapat digenerate dan dinormalisasi berdasarkan spesialisasi serta tanggal. Admin juga dapat memperbarui status antrian dan mereset antrian hari berjalan. fileciteturn67file0 fileciteturn76file0

### 6. Dashboard Admin

Dashboard admin menampilkan antara lain:

- statistik reservasi hari ini;
- jumlah berdasarkan status;
- statistik reservasi per dokter;
- antrian harian;
- jumlah pasien yang telah dilayani;
- total reservasi.

fileciteturn72file0

### 7. Dashboard dan Pemeriksaan Dokter

Dokter dapat:

- melihat reservasi miliknya;
- membuka detail pasien;
- melihat riwayat pemeriksaan;
- memasukkan diagnosis;
- memasukkan resep;
- menambahkan catatan dokter;
- menyelesaikan pemeriksaan;
- mencetak data pemeriksaan dalam PDF.

Ketika pemeriksaan diselesaikan, sistem membuat/memperbarui `MedicalRecord`, mengubah status appointment menjadi `completed`, dan memperbarui status queue menjadi `served`. fileciteturn63file0

### 8. Rekam Medis

Rekam medis terhubung langsung dengan appointment. Appointment memiliki relasi satu-ke-satu dengan `MedicalRecord`, sehingga data diagnosis dan resep dapat dikaitkan dengan kunjungan tertentu. fileciteturn68file0

### 9. Notifikasi

`NotificationService` menyediakan notifikasi untuk beberapa kejadian penting:

- reservasi baru ke admin;
- reservasi baru ke dokter;
- perubahan status antrian ke admin;
- pemanggilan antrian ke pasien;
- perubahan jadwal ke dokter;
- persetujuan reservasi ke pasien;
- pembatalan reservasi ke pasien;
- mark as read;
- mark all as read;
- penghapusan notifikasi.

fileciteturn65file0

### 10. Laporan Kunjungan

Admin memiliki modul laporan dengan filter berdasarkan:

- dokter;
- rentang tanggal;
- status.

Laporan menyediakan statistik seperti total kunjungan, selesai, pending, dibatalkan, rata-rata waktu tunggu, dan statistik per dokter. Hasil dapat diekspor ke PDF dan Excel. fileciteturn64file0

---

## Peran Pengguna

### Admin

Area utama:

```text
/admin/dashboard
/admin/patients
/admin/doctors
/admin/approvals
/admin/queues
/admin/appointments
/admin/reports/visitation
```

Admin berperan sebagai pengelola data dan operasional klinik.

### Dokter

Area utama:

```text
/dokter/dashboard
/dokter/reservasi/riwayat
/dokter/reservasi/{appointment}
```

Dokter hanya dapat mengakses appointment yang terkait dengan akun dokternya. Controller melakukan pengecekan `doctor_id` terhadap dokter yang sedang login sebelum detail atau data pemeriksaan diakses. fileciteturn63file0

### Pasien

Area utama:

```text
/pasien/dashboard
/pasien/profile
/pasien/reservasi
/pasien/reservasi/riwayat
/pasien/reservasi/{appointment}
```

Pasien hanya dapat membuka appointment miliknya sendiri; controller melakukan pemeriksaan `patient_id` sebelum detail reservasi ditampilkan. fileciteturn62file0

---

## Alur Bisnis

### Registrasi Pasien

```text
Pasien
  ↓
Form Registrasi
  ↓
Validasi
  ↓
Create User (role: pasien)
  ↓
Create Patient Profile
  ↓
Transaction Commit
  ↓
Auto Login
  ↓
Dashboard Pasien
```

### Reservasi

```text
Pasien Login
    ↓
Pilih Dokter
    ↓
Pilih Jadwal
    ↓
Pilih Tanggal
    ↓
Validasi Hari Praktik
    ↓
Generate Booking Code
    ↓
Generate Queue Number
    ↓
Create Appointment + Queue
    ↓
Commit Transaction
    ↓
Kirim Email Konfirmasi
    ↓
Notifikasi Admin + Dokter
```

Implementasi reservasi menggunakan transaction agar appointment dan queue tidak tersimpan secara parsial ketika terjadi kegagalan proses. fileciteturn62file0

### Pemeriksaan

```text
Appointment
    ↓
Dokter Membuka Reservasi
    ↓
Diagnosis / Resep / Catatan
    ↓
Medical Record
    ↓
Appointment = completed
    ↓
Queue = served
```

fileciteturn63file0

---

## Arsitektur

Project menggunakan arsitektur MVC Laravel dengan pemisahan controller berdasarkan konteks pengguna.

```text
Browser
   │
   ▼
routes/web.php
   │
   ├── Auth Controllers
   │
   ├── Admin Controllers
   │
   ├── Dokter Controllers
   │
   └── Pasien Controllers
   │
   ▼
Models / Services / Notifications
   │
   ▼
Eloquent ORM
   │
   ▼
MySQL
```

Struktur controller menunjukkan pemisahan namespace `Admin`, `Dokter`, dan `Pasien`, disertai controller umum seperti appointment, doctor, patient, queue, medical record, dan notification. fileciteturn55file0

---

## Teknologi

### Backend

- PHP `^8.1`
- Laravel `^10.10`
- Laravel Sanctum
- Laravel Tinker
- Guzzle
- Laravel Eloquent ORM

### Reporting

- `barryvdh/laravel-dompdf` untuk PDF
- `maatwebsite/excel` untuk Excel

Dependency tersebut tercantum pada `composer.json`. fileciteturn51file0

### Frontend

- Blade
- Vite 5
- Axios

Project tidak menggunakan React, Vue, atau Angular. Asset frontend dibangun menggunakan Laravel Vite Plugin. fileciteturn52file0

### Database

- MySQL
- Laravel Migrations
- Laravel Seeders

---

## Struktur Project

```text
reservasi_dokter/
├── app/
│   ├── Console/
│   ├── Events/
│   ├── Exports/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       ├── Auth/
│   │       ├── Dokter/
│   │       └── Pasien/
│   ├── Listeners/
│   ├── Mail/
│   ├── Models/
│   ├── Notifications/
│   ├── Providers/
│   └── Services/
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── dokter/
│       ├── emails/
│       ├── layouts/
│       ├── notifications/
│       └── pasien/
│
├── routes/
│   └── web.php
│
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── DESIGN.md
└── README.md
```

Struktur view secara eksplisit memisahkan UI admin, dokter, pasien, autentikasi, email, dan notifikasi. fileciteturn77file0

---

## Model Data

Relasi inti aplikasi dapat digambarkan sebagai berikut:

```text
User
 ├── Doctor
 │    ├── Specialization
 │    ├── Schedule
 │    └── Appointment
 │
 └── Patient
      └── Appointment
             ├── Queue
             ├── MedicalRecord
             ├── Schedule
             └── Doctor
```

### Entitas utama

| Entitas | Peran |
|---|---|
| `User` | Akun autentikasi dan role |
| `Doctor` | Profil dokter |
| `Patient` | Profil pasien |
| `Specialization` | Spesialisasi dokter |
| `Schedule` | Jadwal praktik |
| `Appointment` | Data reservasi |
| `Queue` | Nomor dan status antrian |
| `MedicalRecord` | Hasil pemeriksaan |
| `Notification` | Notifikasi pengguna |

Migration appointment membuat foreign key ke pasien, dokter, dan jadwal, sedangkan queue terhubung ke appointment. fileciteturn73file0 fileciteturn75file0

---

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/alfajauhari068/reservasi_dokter.git
cd reservasi_dokter
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Buat file environment

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Buat database MySQL

Contoh:

```sql
CREATE DATABASE reservasi_klinik;
```

Database default pada `.env.example` menggunakan nama `reservasi_klinik`. fileciteturn59file0

### 7. Konfigurasi `.env`

Minimal:

```env
APP_NAME="Reservasi Dokter"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservasi_klinik
DB_USERNAME=root
DB_PASSWORD=
```

### 8. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

### 9. Jalankan Vite

Development:

```bash
npm run dev
```

Production build:

```bash
npm run build
```

### 10. Jalankan Laravel

```bash
php artisan serve
```

Aplikasi kemudian dapat diakses melalui:

```text
http://127.0.0.1:8000
```

---

## Konfigurasi Environment

`.env.example` menyediakan konfigurasi untuk:

- aplikasi;
- MySQL;
- cache;
- filesystem;
- queue;
- session;
- Redis;
- SMTP/Mailpit;
- AWS S3;
- Pusher/Vite.

Untuk development, database menggunakan MySQL dan queue default menggunakan `sync`. fileciteturn59file0

Untuk production:

```env
APP_ENV=production
APP_DEBUG=false
```

Jangan commit `.env` atau credential sebenarnya ke repository.

---

## Database dan Seeder

Repository menyediakan migration untuk domain utama sistem, termasuk:

- users dan role;
- specializations;
- doctors;
- patients;
- schedules;
- appointments;
- queues;
- serta domain lain yang mendukung pemeriksaan, notifikasi, dan laporan.

fileciteturn56file0

### Seeder

`DatabaseSeeder` menyediakan data development untuk:

- admin;
- 3 dokter contoh;
- beberapa pasien contoh;
- spesialisasi;
- appointment contoh;
- queue contoh.

fileciteturn66file0

Reset total database development:

```bash
php artisan migrate:fresh --seed
```

> **Peringatan:** perintah tersebut menghapus seluruh tabel pada database yang ditargetkan. Jangan jalankan terhadap database production.

---

## Akun Development

Seeder saat ini menggunakan credential berikut untuk data demo:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@klinik.test` | `password` |
| Dokter | `dokter1@klinik.test` | `password` |
| Dokter | `dokter2@klinik.test` | `password` |
| Dokter | `dokter3@klinik.test` | `password` |
| Pasien | `pasien1@klinik.test` | `password` |
| Pasien | `pasien2@klinik.test` | `password` |
| Pasien | `pasien3@klinik.test` | `password` |

Credential tersebut **khusus development/demo** dan tidak boleh digunakan pada production. Seeder menyimpan password dalam bentuk hash, tetapi password sumbernya memang `password`. fileciteturn66file0

---

## Routing

Semua route web utama berada di:

```text
routes/web.php
```

### Public & Authentication

```text
/
/login
/register
/logout
```

### Admin

```text
/admin/dashboard
/admin/patients
/admin/doctors
/admin/approvals
/admin/queues
/admin/appointments
/admin/reports/visitation
/admin/reports/visitation/export-pdf
/admin/reports/visitation/export-excel
```

Area admin dilindungi middleware:

```text
auth + role:admin
```

### Dokter

```text
/dokter/dashboard
/dokter/reservasi/riwayat
/dokter/reservasi/{appointment}
/dokter/reservasi/{appointment}/print-pdf
```

Dilindungi:

```text
auth + role:dokter
```

### Pasien

```text
/pasien/dashboard
/pasien/profile
/pasien/reservasi
/pasien/reservasi/riwayat
/pasien/reservasi/{appointment}
```

Dilindungi:

```text
auth + role:pasien
```

### Notifications

```text
/notifications
/notifications/{notification}
/notifications/mark-all-as-read
/notifications/{notification}/mark-as-read
```

Route lengkap dapat diperiksa langsung pada `routes/web.php`. fileciteturn57file0

---

## Notifikasi

Sistem menggunakan Laravel Notifications melalui `NotificationService` untuk mengirim dan mengelola notifikasi internal.

Alur utama:

```text
Reservasi Baru
 ├──> Admin
 └──> Dokter

Antrian Dipanggil
 └──> Pasien

Jadwal Berubah
 └──> Dokter

Reservasi Disetujui / Dibatalkan
 └──> Pasien
```

Notifikasi pengguna dapat dibaca, ditandai sebagai telah dibaca, dan dihapus. fileciteturn65file0

Project juga memiliki endpoint debug notifikasi pada `routes/web.php`. Endpoint debug tersebut **sebaiknya tidak diaktifkan pada production** karena dapat memicu pengiriman notification secara langsung. fileciteturn57file0

---

## Laporan dan Export

Modul laporan menggunakan service khusus `VisitReportService` untuk membangun query laporan yang dapat digunakan kembali.

Output tersedia dalam:

- halaman laporan;
- PDF;
- Excel.

Contoh:

```text
/admin/reports/visitation
/admin/reports/visitation/export-pdf
/admin/reports/visitation/export-excel
```

Filter utama mencakup dokter, tanggal mulai, tanggal akhir, dan status. fileciteturn64file0

---

## Testing

Repository memiliki struktur Laravel testing:

```text
tests/
├── Feature/
├── Unit/
├── CreatesApplication.php
└── TestCase.php
```

fileciteturn78file0

Jalankan test dengan:

```bash
php artisan test
```

Atau:

```bash
./vendor/bin/phpunit
```

README ini hanya mengonfirmasi bahwa struktur testing tersedia; **hasil eksekusi test tidak diklaim di sini karena audit repository dilakukan dari source code, bukan dari runtime lokal aplikasi**.

---

## Perintah Pengembangan

```bash
# Install PHP dependency
composer install

# Install frontend dependency
npm install

# Generate application key
php artisan key:generate

# Migration
php artisan migrate

# Migration + seed
php artisan migrate --seed

# Reset database development
php artisan migrate:fresh --seed

# Clear cache
php artisan optimize:clear

# List routes
php artisan route:list

# Run Laravel
php artisan serve

# Run Vite development server
npm run dev

# Build frontend production
npm run build

# Run tests
php artisan test
```

---

## Catatan Keamanan

Project mengelola data yang secara potensial sensitif, terutama data pasien dan rekam medis. Karena itu beberapa area perlu diperlakukan lebih ketat daripada aplikasi CRUD biasa.

### 1. Credential Seeder

Credential `password` pada akun demo harus diganti sebelum deployment. fileciteturn66file0

### 2. Debug Routes

`routes/web.php` saat ini masih memiliki endpoint seperti:

```text
/debug
/debug-logout
/debug-dashboard-data
/notifications/debug-send
```

Endpoint tersebut berguna selama development, tetapi **tidak seharusnya tersedia pada production** karena dapat membuka informasi session/data atau memicu tindakan internal. fileciteturn57file0

### 3. Data Kesehatan

Data seperti keluhan, diagnosis, resep, dan catatan dokter harus dianggap sebagai data sensitif. Production membutuhkan kontrol akses, logging, backup, dan kebijakan retensi data yang sesuai.

### 4. Authorization

Role middleware sudah digunakan pada route utama. Selain itu controller dokter dan pasien melakukan object-level authorization terhadap appointment yang diakses. fileciteturn62file0 fileciteturn63file0

### 5. Upload Foto

Upload foto dokter dibatasi sebagai image dan maksimal 2048 KB. File disimpan pada disk `public`. fileciteturn61file0

### 6. Production Environment

Gunakan:

```env
APP_DEBUG=false
```

dan jangan pernah menyimpan credential database, SMTP, cloud storage, atau secret lain pada Git repository.

---

## Catatan Audit Repository

Audit source code menunjukkan beberapa karakteristik yang penting untuk dipahami sebelum project dianggap production-ready.

### Status positif

- Laravel 10 digunakan secara konsisten sebagai framework utama. fileciteturn51file0
- Pemisahan role admin/dokter/pasien sudah diterapkan pada routing.
- Appointment dan queue menggunakan relasi database.
- Reservasi menggunakan transaction.
- Password menggunakan hashing Laravel.
- Session diregenerasi setelah login.
- Terdapat object-level authorization pada akses appointment dokter dan pasien.
- Modul PDF dan Excel benar-benar memiliki dependency dan controller implementasi.
- Struktur service dan notification sudah mulai dipisahkan dari controller.

### Area yang perlu diperhatikan

1. **Status appointment tidak sepenuhnya konsisten.** Migration awal mendefinisikan `pending`, `approved`, `cancelled`, dan `done`, sementara beberapa controller juga menggunakan `in_progress` dan `completed`. fileciteturn73file0 fileciteturn72file0 fileciteturn63file0
2. **Reservasi pasien saat ini otomatis mengisi `approval_status = approved` dan menampilkan pesan bahwa reservasi otomatis disetujui**, sehingga alur approval admin tidak menjadi gate utama untuk reservasi yang dibuat melalui controller pasien. fileciteturn62file0
3. **Debug endpoint masih berada di `web.php`** dan perlu dinonaktifkan/dihapus sebelum deployment production. fileciteturn57file0
4. **Seeder menggunakan password demo yang sederhana.** Ini tepat untuk development, tidak tepat untuk production. fileciteturn66file0
5. **Testing sudah memiliki struktur, tetapi coverage aktual harus diukur dengan menjalankan suite.** Repository source saja tidak cukup untuk menyimpulkan seluruh workflow bebas bug.
6. **Repository belum mendeklarasikan license pada metadata GitHub.** Jika project akan dipublikasikan sebagai open-source, tentukan license yang sesuai.

README ini sengaja membedakan antara **fitur yang dapat diverifikasi dari source code** dan klaim production-readiness. Audit dokumentasi bukan pengganti penetration test atau pengujian end-to-end.

---

## Design System

Repository memiliki dokumen `DESIGN.md` yang mendokumentasikan analisis design system, termasuk token warna, typography, dan prinsip visual. Dokumen tersebut menjadi referensi tambahan untuk implementasi UI. fileciteturn58file0

---

## Dokumentasi Tambahan

File berikut juga tersedia pada repository:

```text
DESIGN.md
ROUTES_WEB_EXAMPLE.php
```

`DESIGN.md` digunakan sebagai dokumentasi desain, sedangkan file `ROUTES_WEB_EXAMPLE.php` merupakan contoh/reference route dan bukan pengganti `routes/web.php` yang aktif.

---

## Lisensi

Repository GitHub saat ini tidak menetapkan license metadata. Jika project akan dipublikasikan sebagai open-source, tambahkan file `LICENSE` dan pilih lisensi yang sesuai dengan kebutuhan pemilik project.

Dependency pihak ketiga tetap tunduk pada lisensinya masing-masing.

---

## Repository

urlGitHub — reservasi_dokterhttps://github.com/alfajauhari068/reservasi_dokter
