# Sistem Informasi Pelaporan Notaris

Sistem berbasis web untuk penggantian pengumpulan laporan bulanan/tahunan notaris yang sebelumnya memakai Google Forms. Notaris mengirim laporan terstruktur beserta file PDF, sementara admin wilayah dan superadmin memantau, merekapitulasi, serta melacak kepatuhan pelaporan per wilayah.

Aplikasi ini dibangun dengan identitas visual **Kantor Wilayah Kementerian Hukum dan HAM Bengkulu**.

## Fitur

### Role Notaris
- Akun dibuat/didaftarkan oleh **admin wilayah** (bukan daftar mandiri)
- Dashboard riwayat laporan yang pernah dikirim
- Input laporan bulanan: bulan & tahun, jumlah akta, legalisasi (disahkan), waarmerking (dibukukan), wasiat, protes
- Upload file laporan (PDF, maks. 10 MB) — **unik per bulan** (tidak bisa duplikat)

### Role Admin Wilayah (per wilayah)
- Daftarkan & kelola notaris di wilayahnya sendiri
- Dashboard, laporan, rekapitulasi, tracking kepatuhan — **selalu terbatas pada wilayahnya**

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
| Web server | Nginx 1.28 + PHP-FPM (php-cgi) |

## Struktur Utama

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Dashboard, Laporan, Rekapitulasi, Tracking, Notaris, Admin Wilayah
│   │   ├── Auth/           # Auth Breeze (login, reset password)
│   │   ├── DashboardController.php
│   │   └── ReportController.php  # CRUD laporan notaris
│   └── Middleware/         # EnsureAdmin, EnsureSuperAdmin
├── Models/                 # User, Region, Report
database/
├── migrations/             # regions, users, reports, role/region expand
└── seeders/DatabaseSeeder.php
resources/views/
├── components/             # Button, input, auth-card, layouts/partials (logo, header, footer)
├── layouts/                # app (autentikasi), guest (auth), navigation
├── auth/                   # login, forgot/reset password
├── admin/                  # dashboard, laporan, rekapitulasi, tracking, region-admins, notaris
├── reports/                # form input laporan
└── welcome.blade.php       # landing page institusional (3 tombol wilayah)
```

## Instalasi Lokal

### 1. Prasyarat
- PHP 8.2+ (dengan ekstensi `pdo_mysql`, `fileinfo`, `mbstring`, `openssl`, `zip`)
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

Database yang dipakai bernama `maganghub` — buat dulu jika belum ada:

```sql
CREATE DATABASE maganghub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Akun awal (seed)

| Role | Email | Password |
|---|---|---|
| Superadmin | `admin@example.com` | `admin123` |
| Admin Wilayah — SIMAKUTENG | `adm.simakuteng@example.com` | `admin123` |
| Admin Wilayah — RELEPARMU | `adm.releparmu@example.com` | `admin123` |
| Admin Wilayah — KOTA BENGKULU | `adm.kotabengkulu@example.com` | `admin123` |
| Notaris (SIMAKUTENG) | `notaris1@example.com` | `notaris123` |
| Notaris (KOTA BENGKULU) | `notaris2@example.com` | `notaris123` |

> Ganti password segera setelah masuk ke lingkungan produksi.

### 4. Login berbasis wilayah

- Landing page menampilkan 3 tombol wilayah: **SIMAKUTENG**, **RELEPARMU**, **KOTA BENGKULU**.
- Pencet salah satu → halaman login wilayah tersebut. Akun notaris/admin wilayah dari wilayah lain **ditolak**.
- **Superadmin** masuk lewat URL langsung: `http://127.0.0.1:8080/admin/login`.

## Menjalankan

### Opsi A — Server bawaan (development)

```bash
php artisan serve
# buka http://127.0.0.1:8000
```

### Opsi B — Nginx + PHP-FPM (direkomendasikan)

Instalasi nginx di `D:\App\nginx\nginx-1.28.1\` dengan konfigurasi:
- Listen port **8080** (port 80 dipakai Apache XAMPP)
- Root → `maganghub/public`
- PHP-FPM via `php-cgi.exe` XAMPP pada `127.0.0.1:9000`
- Blokir akses file tersembunyi (`.env`), `client_max_body_size 12m`, gzip + cache aset

Start (jalankan sekali, atau setelah restart komputer):

```powershell
powershell -ExecutionPolicy Bypass -File D:\App\nginx\start-php-cgi.ps1
powershell -ExecutionPolicy Bypass -File D:\App\nginx\start-nginx.ps1
```

Kemudian buka `http://127.0.0.1:8080`.

### Akses dari perangkat lain (LAN)

nginx sudah bind `0.0.0.0:8080` dan rule firewall TCP 8080 sudah dibuka untuk semua profil jaringan. Dari perangkat lain di jaringan yang sama, buka:

```
http://<IP-laptop>:8080
```

Cek IP dengan `ipconfig`. Jika jaringan memblokir antar-perangkat (AP isolation di kantor), gunakan **hotspot HP** (laptop connect ke hotspot HP) atau **Mobile Hotspot Windows** — saat laptop jadi hotspot, IP host-nya `192.168.137.1`, akses via `http://192.168.137.1:8080`.

Jika tetap gagal: periksa **AP Isolation** di pengaturan WiFi router, atau cek IP dengan `ipconfig`.

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

Suite mencakup: migrasi role & region, login wilayah (penolakan lintas wilayah), submit laporan + admin melihatnya, penolakan laporan duplikat, tracking notaris belum lapor, RBAC (notaris tidak akses admin, admin wilayah tidak akses superadmin), pendaftaran notaris oleh admin wilayah, dan pendaftaran admin wilayah oleh superadmin.

## Deployment

Sebelum deploy ke hosting/VPS:
1. `APP_ENV=production` dan `APP_DEBUG=false`
2. Jalankan `php artisan config:cache` dan `php artisan route:cache`
3. Atur SMTP nyata pada `MAIL_*` (saat ini `MAIL_MAILER=log`)
4. Migrasi + seed, lalu ubah password default
5. Arahkan domain ke `public/` document root (jangan ke root project)

## Lisensi

Aplikasi dikembangkan untuk keperluan internal Kanwil Kemenkumham Bengkulu.
