# Design: Import Data Notaris dari Excel ke Database

**Tanggal:** 2026-08-16
**Status:** Disetujui user
**Target:** `D:\App\Xampp\htdocs\maganghub`

## 1. Ringkasan

Import daftar notaris Provinsi Bengkulu (±136 orang aktif) dari file Excel
`Data Notaris Provinsi Bengkulu 2026 Final.xlsx` (sheet "Total Notaris", kolom A-U)
ke tabel `users` dengan role `notaris` dan region sesuai kabupaten penempatan.

## 2. Keputusan (disepakati via brainstorming)

1. **Email** digenerate otomatis: `notaris.{nik}@notaris.local` (data tidak punya email)
2. **Pemetaan kabupaten → region** (kolom H dinormalisasi lowercase):
   - `KOTA BENGKULU` → KOTA BENGKULU
   - `REJANG LEBONG`, `KEPAHIANG`, `LEBONG` → RELEPARMU
   - `SELUMA`, `BENGKULU SELATAN`, `KAUR`, `BENGKULU UTARA`, `MUKO MUKO`, `BENGKULU TENGAH` → SEMAKUTENG
3. **Filter status:** import yang `aktif` + baris status KOSONG yang punya No BA Sumpah
   (no.117-136, 20 orang). Skip `Notaris Belum Aktif` (13) dan baris kosong total
   (Melly Novianti).
4. **NIK:** dari kolom F; jika kosong, ambil 16 digit terakhir dari kolom E
   (kasus NPWP+NIK tergabung: APRIANI ASTUTI, NURUL IMAMAH). Jika tetap kosong → skip.
5. **Deduplikasi:** by email/NIK — duplikat ANIKA DEWI hanya 1 yang masuk.

## 3. Implementasi

- Seeder baru: `database/seeders/NotarisImportSeeder.php`
- Jalankan via: `php artisan db:seed --class=NotarisImportSeeder`
- File Excel disalin ke `storage/app/import/data-notaris.xlsx` (tidak tergantung OneDrive)
- Password default semua: `notaris123`
- Tidak menyentuh akun seed yang ada (superadmin, admin_wilayah, notaris1, notaris2)

## 4. Testing

- Test baru memverifikasi seeder: filter status, mapping region, deduplikasi,
  format email dari NIK.
