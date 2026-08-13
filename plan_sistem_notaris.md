# Rencana Pengembangan Sistem Laporan Bulanan Notaris

## 1. Deskripsi Proyek
Sistem ini adalah aplikasi berbasis web yang dirancang untuk menggantikan pengumpulan laporan bulanan notaris yang sebelumnya menggunakan Google Forms. Sistem ini memungkinkan notaris untuk mengirimkan laporan terstruktur beserta file PDF, dan memungkinkan admin untuk memantau, merekapitulasi, dan melacak kepatuhan pelaporan notaris berdasarkan wilayah.

## 2. Tech Stack
*   **Backend:** Laravel (PHP)
*   **Database:** MySQL
*   **Frontend:** Blade Templates + Tailwind CSS (atau Bootstrap) + Alpine.js (opsional untuk interaktivitas form)
*   **Authentication:** Laravel Breeze / UI
*   **File Storage:** Local Storage / Symlink public (untuk file PDF laporan)

## 3. Aktor & Fitur (Role-Based Access Control)

### A. Role: Notaris (User)
*   **Registrasi & Login:** Mendaftar menggunakan email dan memilih wilayah penempatan (contoh: MPD 1, MPD 2, Simakuteng).
*   **Dashboard Notaris:** Melihat riwayat laporan yang sudah dikirim (tahunan & bulanan).
*   **Input Laporan Bulanan:** Mengisi form pelaporan yang terdiri dari:
    *   Bulan & Tahun Laporan
    *   Jumlah Daftar Akta
    *   Jumlah Daftar Surat Dibawah Tangan Yang Disahkan (Legalisasi)
    *   Jumlah Daftar Surat Dibawah Tangan Yang Dibukukan (Waarmerking)
    *   Jumlah Daftar Wasiat
    *   Jumlah Daftar Protes
    *   Upload File Laporan Bulanan (Format PDF)

### B. Role: Admin
*   **Login Admin:** Akses khusus ke dashboard manajemen.
*   **Dashboard Utama:** Statistik cepat jumlah laporan masuk bulan ini per wilayah.
*   **Manajemen Wilayah & User:** Mengelola master data wilayah dan melihat daftar notaris terdaftar.
*   **Rekapitulasi Tahunan & Bulanan:**
    *   Melihat tabel rekapitulasi data tahunan (total akta, dll per tahun).
    *   Fitur "Drill-down": Klik tahun tertentu untuk melihat rincian per bulan.
    *   Filter berdasarkan Wilayah (Dropdown).
*   **Tracking Kepatuhan (Not Submitted List):** 
    *   Sistem dapat membandingkan jumlah notaris terdaftar di suatu wilayah dengan jumlah laporan masuk pada bulan/tahun tertentu.
    *   Menampilkan daftar nama notaris yang **belum** mengirimkan laporan pada bulan yang dipilih.
*   **Manajemen Laporan:** Melihat detail laporan individu dan mengunduh (download) file PDF yang diunggah notaris.

## 4. Desain Database (Schema)

### Table: `regions` (Wilayah)
*   `id` (PK)
*   `name` (String) - ex: MPD, Simakuteng, MPD Lainnya

### Table: `users`
*   `id` (PK)
*   `name` (String) - Nama Notaris / Admin
*   `email` (String, Unique)
*   `password` (String)
*   `role` (Enum/String) - 'admin', 'notaris'
*   `region_id` (FK -> regions.id, Nullable untuk admin)

### Table: `reports` (Laporan)
*   `id` (PK)
*   `user_id` (FK -> users.id)
*   `region_id` (FK -> regions.id) - *Denormalisasi opsional untuk kemudahan query*
*   `report_month` (Integer/String) - ex: 1 s/d 12
*   `report_year` (Integer) - ex: 2024, 2025
*   `jumlah_akta` (Integer)
*   `jumlah_disahkan` (Integer)
*   `jumlah_dibukukan` (Integer)
*   `jumlah_wasiat` (Integer)
*   `jumlah_protes` (Integer)
*   `file_path` (String) - Lokasi file PDF di server
*   `created_at`, `updated_at` (Timestamp)
*   *Constraint:* Unique (user_id, report_month, report_year) agar tidak ada duplikasi laporan di bulan yang sama.

## 5. Alur Logika Penting (Algoritma)

1.  **Mencari Notaris yang Belum Lapor:**
    *   Admin memilih Bulan `X` dan Tahun `Y`, serta Wilayah `Z`.
    *   Ambil semua `user_id` dari tabel `users` dimana `role = notaris` dan `region_id = Z`.
    *   Ambil semua `user_id` dari tabel `reports` dimana `report_month = X` dan `report_year = Y` dan `region_id = Z`.
    *   Lakukan perbandingan (Array Diff). `User` yang ada di daftar pertama tapi tidak ada di daftar kedua adalah mereka yang belum lapor.

2.  **Rekapitulasi Tahunan ke Bulanan:**
    *   **View Tahunan:** `SELECT report_year, SUM(jumlah_akta) ... FROM reports GROUP BY report_year`.
    *   Di UI, berikan link pada tahun (misal 2024) yang mengarah ke `route('reports.monthly', ['year' => 2024])`.
    *   **View Bulanan:** `SELECT report_month, SUM(jumlah_akta) ... FROM reports WHERE report_year = 2024 GROUP BY report_month`.

## 6. Fase Pengembangan (Roadmap)
*   **Fase 1: Setup & Autentikasi.** Instalasi Laravel, konfigurasi database, pembuatan tabel, dan sistem Login/Register (pilih wilayah).
*   **Fase 2: CRUD Laporan (User).** Pembuatan form input laporan untuk Notaris beserta fungsionalitas upload PDF.
*   **Fase 3: Dashboard Admin & Filter.** Tampilan tabel data laporan untuk admin, ditambah filter berdasarkan wilayah.
*   **Fase 4: Rekapitulasi & Tracking.** Pembuatan fitur View Tahunan/Bulanan dan logika pencarian Notaris yang belum melapor.
*   **Fase 5: UI/UX & Testing.** Merapikan tampilan dengan Tailwind CSS agar user-friendly, testing keamanan file PDF, dan deployment.
