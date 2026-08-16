# Design: Grafik & Daftar Kepatuhan di Dashboard Notaris

**Tanggal:** 2026-08-13
**Status:** Disetujui user
**Target:** `D:\App\Xampp\htdocs\maganghub`

## 1. Ringkasan

Menambahkan visualisasi kepatuhan pelaporan di **Dashboard Notaris** (`/dashboard`): grafik bar sederhana "Sudah Melapor" vs "Belum Melapor" untuk **bulan berjalan**, lingkup **wilayah notaris yang login**, plus daftar nama notaris yang belum melapor. Tujuan sosial: menimbulkan rasa malu/tekanan sebaya agar notaris terdorong melapor.

## 2. Data & Logika

**Controller:** `app/Http/Controllers/DashboardController.php`

- `$month = now()->month`, `$year = now()->year`
- `$regionId = $request->user()->region_id`
- `totalNotaris` = jumlah user `role=notaris` di `$regionId`
- `sudahMelapor` = count distinct `user_id` pada tabel `reports` dengan `region_id=$regionId`, `report_month=$month`, `report_year=$year`
- `belumMelapor = totalNotaris - sudahMelapor`
- `daftarBelum` = koleksi user notaris di wilayah yang **belum** submit untuk periode tersebut (reuse pola `RecapController::tracking`)
- Nama wilayah diambil dari `$request->user()->region?->name`

## 3. UI

**View:** `resources/views/dashboard.blade.php`

Menambahkan satu card baru setelah "Panduan Pelaporan":

- Header: **"Kepatuhan Pelaporan Wilayah {nama}"** + badge periode (Bulan + Tahun)
- **Bar chart CSS-only** (tanpa library JS baru):
  - Bar "Sudah Melapor" — hijau (`bg-green-500`), panjang proporsional, label `jumlah orang (persentase%)`
  - Bar "Belum Melapor" — merah (`bg-red-500`), label jumlah orang
  - Panjang bar dihitung via persentase terhadap total (`style="width: X%"`)
- **Daftar "Belum Melapor"** (muncul hanya jika `belumMelapor > 0`): list nama dengan badge merah, pesan ajakan
- Jika semua sudah melapor: tampilkan pesan hijau "Semua notaris di wilayah ini sudah melapor"

## 4. Error Handling

- Jika user tidak punya `region_id` (tidak mungkin untuk notaris, tapi dijaga): `daftarBelum` kosong, `totalNotaris=0`, grafik tidak dirender (blok `@if($totalNotaris > 0)`)

## 5. Testing

- `tests/Feature/DashboardComplianceTest.php`:
  - Notaris dengan laporan bulan berjalan → `sudahMelapor` bertambah, `daftarBelum` berkurang
  - Notaris tanpa laporan → muncul di `daftarBelum`
  - Hanya menghitung notaris wilayah yang sama (bukan wilayah lain)
  - Rute `/dashboard` diakses role `notaris` OK
