# Desain: Role Superadmin, Admin Wilayah, & Login Berbasis Wilayah

> **Jenis:** Design Spec
> **Tanggal:** 2026-08-12
> **Proyek:** Sistem Informasi Pelaporan Notaris — Kanwil Kemenkumham Bengkulu
> **Status:** Disetujui user (brainstorming)

---

## 1. Tujuan

1. Mengubah `admin` menjadi **superadmin** dengan akses penuh semua wilayah.
2. Menambah role **admin wilayah** yang bertugas mendaftarkan peserta (notaris) di wilayahnya sendiri.
3. Halaman depan mengganti tombol "Masuk" & "Daftar Notaris" menjadi tombol 3 wilayah.
4. Login dikunci per wilayah (akun region lain ditolak); superadmin login via URL langsung.

## 2. Perubahan Database

### Table `regions`
- Rename data menjadi: **SIMAKUTENG**, **RELEPARMU**, **KOTA BENGKULU**.
- Hapus baris `MPD Lainnya`.
- Tambah kolom `slug` (string, unique): `simakuteng`, `releparmu`, `kota-bengkulu`.

### Table `users` (kolom `role`)
- Nilai berubah: `admin` → `superadmin`, tambah nilai `admin_wilayah`.
- Nilai akhir: `superadmin | admin_wilayah | notaris`.

### Migrasi (satu migrasi baru `2026_08_12_000000_expand_roles_and_regions.php`)
- Tambah kolom `slug` + isi data regions baru.
- Update `role='admin'` → `role='superadmin'` pada users.
- Tambah konstanta/helper role di model `User`: `isSuperAdmin()`, `isAdminWilayah()`, `isNotaris()`.

## 3. Alur Login

- **Landing (`/`)**: 3 tombol wilayah (`SIMAKUTENG`, `RELEPARMU`, `KOTA BENGKULU`) → `GET /login/{slug}`.
- **`GET /login/{slug?}`**: halaman login menampilkan badge wilayah. Breeze route `login` diubah untuk menerima slug opsional.
- **POST login**: setelah `$request->authenticate()`, validasi tambahan:
  - Jika user role `notaris` atau `admin_wilayah` dan `region.slug != {slug}` → gagal login, pesan "Akun tidak terdaftar di wilayah ini."
  - Superadmin: lewat tanpa cek wilayah.
- **Superadmin**: `GET /admin/login` (sama seperti `/login` tanpa kunci wilayah) — tidak ada tautan publik di landing.
- Redirect setelah login tetap berbasis role: superadmin → `/admin`, admin_wilayah → `/admin` (dashboard discope), notaris → `/dashboard`.

## 4. Role & Scope

### Superadmin (`/admin`)
- Semua fitur admin seperti sekarang + menu **Admin Wilayah** (buat/kelola akun `admin_wilayah`, pilih region).
- Dropdown filter wilayah tetap tersedia di semua halaman admin.

### Admin Wilayah (`/admin`, discope)
- Dashboard, daftar laporan, rekapitulasi, tracking kepatuhan: **selalu difilter `region_id = region miliknya`**, tanpa dropdown pilih wilayah.
- Menu **Notaris**: daftarkan notaris baru (name, email, password awal) & lihat daftar notaris wilayahnya.
- Tidak bisa mengubah admin wilayah / superadmin.

### Notaris
- Flow input laporan tidak berubah.

## 5. Middleware & Controller

- `EnsureAdmin` → perluas jadi cek `isAdmin()` = superadmin ATAU admin_wilayah (untuk `/admin` umum), ditambah:
  - `EnsureSuperAdmin` (hanya superadmin) untuk menu Admin Wilayah.
- **`Admin\RegionAdminController`** (superadmin only):
  - `index` — daftar admin wilayah
  - `store` — buat akun `admin_wilayah` (name, email, password, region_id)
  - `destroy` — hapus/disable
- **`Admin\NotarisController`** (superadmin + admin_wilayah):
  - `index` — daftar notaris (scope region otomatis)
  - `store` — daftarkan notaris baru (name, email, password awal, region otomatis dari user)
- Perluas `Admin\DashboardController`, `Admin\ReportController`, `Admin\RecapController`:
  - helper `visibleRegionScope()`: admin_wilayah → `where('region_id', own)`, superadmin → opsional filter dropdown.

## 6. Views

- `welcome.blade.php`: ganti CTA hero menjadi 3 tombol wilayah besar (branding masing-masing region), hapus tombol "Masuk"/"Daftar Notaris" dari hero; superadmin tidak tampil di landing.
- `auth/login.blade.php`: terima `$region` (nullable) → tampil badge region, hidden input slug.
- `layouts/navigation.blade.php`: nav admin menambah menu "Admin Wilayah" (hanya superadmin) & "Notaris".
- Halaman baru: `admin/region-admins.blade.php` (daftar+form), `admin/notaris.blade.php` (daftar+form).
- Halaman admin yang sudah ada: dropdown wilayah disembunyikan untuk admin_wilayah.

## 7. Seed

- `admin@example.com/admin123` → `superadmin`.
- Buat 1 admin wilayah per region: `adm.simakuteng@example.com`, `adm.releparmu@example.com`, `adm.kotabengkulu@example.com` (password `admin123`).
- Notaris lama di-migrasi ke region baru sesuai mapping (MPD 1→KOTA BENGKULU, MPD 2→RELEPARMU, Simakuteng→SIMAKUTENG).

## 8. Testing

- Update test yang menggunakan `role='admin'` → `superadmin`.
- Test baru:
  - Login notaris via slug wilayah yang salah → ditolak.
  - Login superadmin dari `admin/login` → sukses.
  - Admin wilayah hanya melihat data regionnya (laporan/tracking).
  - Superadmin membuat admin wilayah.
  - Notaris dibuat oleh admin wilayah.
- `php artisan test` hijau, smoke test via nginx/curl tetap OK.

## 9. Di Luar Scope (YAGNI)

- Tidak ada halaman "Daftar Notaris" publik.
- Tidak ada sistem verifikasi/approval registrasi.
- Tidak ada multi-auth guard terpisah per role.
