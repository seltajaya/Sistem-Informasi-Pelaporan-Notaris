# Sistem Informasi Pelaporan Notaris

Sistem berbasis web untuk penggantian pengumpulan laporan bulanan/tahunan notaris yang sebelumnya memakai Google Forms. Notaris dapat mengirim laporan terstruktur beserta file PDF, dan admin dapat memantau, merekapitulasi, serta melacak kepatuhan pelaporan per wilayah.

Aplikasi ini dibangun dengan identitas visual **Kantor Wilayah Kementerian Hukum dan HAM Bengkulu**.

## Fitur

### Role Notaris
- Akun dibuat/didaftarkan oleh **admin wilayah** (bukan daftar mandiri)
- Dashboard riwayat laporan yang pernah dikirim
- **Grafik kepatuhan wilayah**: bar chart "Sudah/Belum Melapor" bulan berjalan + daftar nama notaris yang belum melapor
- Input laporan bulanan: bulan & tahun, jumlah akta, legalisasi (disahkan), waarmerking (dibukukan), wasiat, protes
- Upload file laporan (PDF, maks. 10 MB) — **unik per bulan** (tidak bisa duplikat)

### Role Admin Wilayah (per wilayah)
- Daftarkan & kelola notaris di wilayahnya sendiri
- Hanya mengakses **Notaris** dan **Kepatuhan** (tracking notaris belum melapor) — **selalu terbatas pada wilayahnya**
- Dashboard, laporan, rekapitulasi, dan kelola admin wilayah **hanya untuk superadmin**

### Role Superadmin
- Akses penuh semua wilayah
- Membuat/mengelola akun admin wilayah
- Melihat laporan, rekapitulasi, dan kepatuhan seluruh wilayah

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Database | MySQL / MariaDB |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js |
| Auth | Laravel Breeze (blade) |
| Import Excel | phpoffice/phpspreadsheet |
| Web server | Nginx 1.28 + PHP-CGI pool (8 proses FastCGI) |

## Struktur Utama

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Dashboard, Laporan, Rekapitulasi & Tracking (admin)
│   │   ├── Auth/           # Auth Breeze
│   │   ├── DashboardController.php   # Dashboard notaris + grafik kepatuhan
│   │   └── ReportController.php  # CRUD laporan notaris
│   └── Middleware/         # EnsureAdmin, EnsureSuperAdmin, EnsureNotaris
├── Models/                 # User, Region, Report
database/
├── migrations/             # regions, users, reports
└── seeders/
    ├── DatabaseSeeder.php      # wilayah + akun awal
    └── NotarisImportSeeder.php # import notaris dari Excel
resources/views/
├── components/             # Button, input, auth-card, layouts/partials (logo, header, footer)
├── layouts/                # app (autentikasi), guest (auth), navigation
├── auth/                   # login wilayah, dll
├── admin/                  # dashboard, laporan, rekapitulasi, tracking, notaris, region-admins
├── reports/                # form input laporan
└── welcome.blade.php       # landing page institusional
```

## Instalasi Lokal

### 1. Prasyarat
- PHP 8.2+ (dengan ekstensi `pdo_mysql`, `fileinfo`, `mbstring`, `openssl`, `zip`, `gd`, `xml` — `gd`/`zip`/`xml` untuk import Excel)
- Composer 2.x
- MySQL / MariaDB
- Node.js + npm (untuk build aset)

### 2. Setup aplikasi

```bash
composer install
npm install
npm run build

# Salin .env dan isi konfigurasi database
copy .env.example .env        # Windows
# atur DB_DATABASE=maganghub, DB_USERNAME, DB_PASSWORD

php artisan key:generate
php artisan migrate --seed     # buat tabel + akun awal + wilayah
```

Aplikasi memakai database bernama `maganghub` — buat dulu jika belum ada:

```sql
CREATE DATABASE maganghub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Akun awal (seed)

| Role | Email | Password |
|---|---|---|
| Superadmin | `admin@example.com` | `admin123` |
| Admin Wilayah — SEMAKUTENG | `adm.semakuteng@example.com` | `admin123` |
| Admin Wilayah — RELEPARMU | `adm.releparmu@example.com` | `admin123` |
| Admin Wilayah — KOTA BENGKULU | `adm.kotabengkulu@example.com` | `admin123` |
| Notaris (SEMAKUTENG) | `notaris1@example.com` | `notaris123` |
| Notaris (KOTA BENGKULU) | `notaris2@example.com` | `notaris123` |

> Ganti password segera setelah masuk ke lingkungan produksi.

### 3b. Import notaris dari Excel (opsional)

Import daftar notaris aktif (±135 orang) dari `storage/app/import/data-notaris.xlsx`
ke akun login. Email otomatis `notaris.{NIK}@notaris.local`, password default `notaris123`,
region mengikuti kabupaten penempatan:

```bash
php artisan db:seed --class=NotarisImportSeeder
```

Seeder **idempoten** (aman dijalankan ulang) dan hanya meng-import status *aktif*
(yang "Belum Aktif"/duplikat/baris kosong di-skip). Ganti password default setelah import.

### 4. Login berbasis wilayah

- Landing page menampilkan 3 tombol wilayah: **SEMAKUTENG**, **RELEPARMU**, **KOTA BENGKULU**.
- Pencet salah satu → halaman login wilayah tersebut. Akun notaris/admin wilayah dari wilayah lain **ditolak**.
- **Superadmin** masuk lewat URL langsung: `http://127.0.0.1:8080/admin/login`.

## Menjalankan

### Opsi A — Server bawaan (development)

```bash
php artisan serve
# buka http://127.0.0.1:8000
```

### Opsi B — Nginx + PHP-CGI pool (direkomendasikan)

Instalasi nginx di `D:\App\nginx\nginx-1.28.1\` dengan konfigurasi:
- Listen port **8080** (port 80 dipakai Apache XAMPP)
- Root → `maganghub/public`
- **Pool 8 proses `php-cgi.exe`** (port 9000-9007) via `upstream phpcgi` — Windows
  php-cgi hanya menangani 1 request per proses, jadi pool wajib untuk banyak user bersamaan
- **OPcache aktif** di `php.ini` (bawaan XAMPP ter-komentar) — tanpa ini setiap request
  compile ulang ribuan file Laravel
- Blokir akses file tersembunyi (`.env`), `client_max_body_size 12m`, gzip + cache aset

Start (jalankan sekali, atau setelah restart komputer):

```powershell
powershell -ExecutionPolicy Bypass -File D:\App\nginx\start-php-cgi.ps1
powershell -ExecutionPolicy Bypass -File D:\App\nginx\start-nginx.ps1
```

Kemudian buka `http://127.0.0.1:8080`.

#### Performa (hasil load test)

| Skenario | Hasil |
|---|---|
| Halaman login, 40 concurrent | ~250 req/s, latency ±160 ms |
| Halaman login, **150 concurrent** | latency ±620 ms, 0 gagal / 600 request |

> Catatan dev: jangan menjalankan `php artisan optimize` lalu `php artisan test`
> di mesin yang sama — cache config membuat suite testing hang. Jalankan
> `php artisan optimize:clear` dulu. Cache produksi dilakukan di server (lihat Deployment).

### Akses dari perangkat lain (LAN)

IP lokal komputer (mis. `192.168.1.10`) — nginx sudah bind `0.0.0.0:8080`, dan rule firewall sudah dibuka:

```
http://192.168.1.10:8080
```

Jika tidak bisa diakses: periksa **AP Isolation** di pengaturan WiFi router, atau cek IP dengan `ipconfig`.

## Identitas / Logo

Header menggunakan logo resmi Kemenkumham jika file tersedia di:

```
public/images/logo-kemenkumham.svg   (atau .png)
```

Tanpa file tersebut, aplikasi menampilkan *placeholder mark* (timbangan emas) + wordmark institusional. Taruh logo resmi pada path di atas untuk memakainya.

## Testing

```bash
php artisan test
```

Suite mencakup:
- RBAC lengkap (notaris/admin wilayah/superadmin, matrix akses per halaman)
- Keamanan: IDOR download laporan, mass assignment role/region, reject upload non-PDF, validasi region_id
- Alur laporan: submit + admin melihat, tolak duplikat bulan yang sama
- Login wilayah (tolak akun lintas wilayah) & redirect per role
- Grafik kepatuhan dashboard notaris (hitungan sudah/belum melapor per wilayah)
- Import Excel (filter status aktif, mapping region, deduplikasi NIK, idempoten)

## Deployment

Sebelum deploy ke hosting/VPS:
1. `APP_ENV=production` dan `APP_DEBUG=false`
2. Di server: `php artisan optimize` (config + route + views cache)
3. Aktifkan **OPcache** pada PHP server
4. Atur SMTP nyata pada `MAIL_*` (saat ini `MAIL_MAILER=log`)
5. Migrasi + seed (+ import notaris jika perlu), lalu ubah semua password default
6. Arahkan domain ke `public/` document root (jangan ke root project)

## Lisensi

Aplikasi dikembangkan untuk keperluan internal Kanwil Kemenkumham Bengkulu.
