# Monev TPP — Web Dashboard SLHS

Aplikasi berbasis **Laravel 12** untuk memonitor dan mengevaluasi kelayakan sanitasi Tempat Pengelolaan Pangan (TPP) di Kota Depok. Sistem ini secara otomatis mengintegrasikan hasil Inspeksi Kesehatan Lingkungan (IKL) dari **Lalapan Depok** dan menghasilkan status kelayakan Sertifikat Laik Higiene Sanitasi (SLHS) berbasis aturan (*rule-based*).

## 🌟 Fitur Utama

- **Manajemen Unit Usaha TPP:** Dikelola per kecamatan dengan pembagian akses spesifik per jenis usaha (SPPG, TPP, DAM, Kantin).
- **Sinkronisasi Otomatis IKL:** Menarik data hasil IKL dari API eksternal **Lalapan Depok** (termasuk *name matching*).
- **Evaluasi Kelayakan Otomatis:** Skor IKL ≥ 80 = "Memenuhi Syarat" (otomatis *real-time*).
- **Dashboard Komprehensif:** Tersedia untuk ringkasan Dinkes (makro), Kecamatan (mikro), dan Publik (tanpa login).
- **Peringatan Dini SLHS:** Notifikasi untuk sertifikat yang hampir/telah kedaluwarsa.
- **Rekap & Ekspor:** Pelaporan ekspor data ke Excel.
- **Resiliensi API:** Penanganan galat otomatis (retry/503/timeout) jika API Lalapan Depok sedang *down*.
- **Isolasi Data Otomatis:** Filter *middleware* berbasis wilayah kerja dan kewenangan admin.

## 👥 Peran dan Akses

1. **Admin Dinkes:** Akses lintas kecamatan, monitoring makro, kelola operator/admin kecamatan, rekap pelaporan pimpinan, dan pantau log sinkronisasi API.
2. **Admin Kecamatan:** Mengelola data unit usaha di wilayah kerjanya. **Catatan Akses:** Hak akses admin kecamatan dipecah dan diisolasi per jenis usaha spesifik (Admin SPPG, Admin TPP, Admin DAM, Admin Kantin) untuk masing-masing kecamatan.
3. **Publik / Guest:** Akses pantau data kelayakan TPP tanpa autentikasi (Read-only).

## 🚀 Lingkungan Pengembangan & Prasyarat

Sistem ini dikembangkan dan dioptimalkan untuk lingkungan berikut:
- **PHP** 8.3.x
- **Laravel** 12.x
- **Composer** 2.x
- **Node.js** & npm
- **Database:** MySQL / MariaDB
- **Local Development:** Direkomendasikan menggunakan **Laragon**
- **Production Environment:** Disiapkan untuk *deployment* pada **cPanel**

## Struktur Folder

```text
app/
├── Exports/
│   └── KelayakanExport.php
├── Http/
│   ├── Controllers/
│   │   ├── GuestController.php
│   │   ├── LoginController.php
│   │   ├── Dinkes/
│   │   │   ├── DashboardController.php
│   │   │   ├── EvaluationController.php
│   │   │   ├── ManageOperatorsController.php
│   │   │   ├── ReportsController.php
│   │   │   └── ReportingController.php
│   │   └── Kecamatan/
│   │       ├── DashboardController.php
│   │       ├── LaporanUnitController.php
│   │       ├── LaporanSasaranController.php
│   │       └── SlhsController.php
│   └── Middleware/
├── Models/
│   ├── FotoUnit.php
│   ├── Kecamatan.php
│   ├── Kelurahan.php
│   ├── LaporanPenerima.php
│   ├── LaporanSlhs.php
│   ├── MenuSppg.php
│   ├── Puskesmas.php
│   ├── SasaranManfaat.php
│   ├── Sppg.php
│   ├── UnitUsaha.php
│   └── User.php
└── Providers/
    └── AppServiceProvider.php

bootstrap/
├── app.php
└── providers.php

config/
├── app.php
├── auth.php
├── cache.php
├── database.php
├── filesystems.php
├── logging.php
├── mail.php
├── queue.php
├── services.php
└── session.php

database/
├── factories/
│   └── UserFactory.php
├── migrations/
└── seeders/

public/
├── css/
├── images/
├── js/
├── storage/
├── index.php
└── robots.txt

resources/
├── css/
├── js/
└── views/
    ├── layout/
    ├── dinkes/
    ├── kecamatan/
    ├── rekapdaerah.blade.php
    └── homepage.blade.php

routes/
├── web.php
└── console.php

storage/
├── app/
├── framework/
└── logs/

tests/
├── Feature/
└── Unit/

## ⚙️ Langkah Instalasi Lokal

1. **Clone repositori**
   ```bash
   git clone <repository-url>
   cd monev-tpp-slhs
   ```

2. **Install dependency PHP & Node**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan koneksi database di `.env`. Tambahkan kredensial API integrasi:
   ```env
   LALAPAN_DEPOK_BASE_URL="https://api.domain.com"
   LALAPAN_DEPOK_API_KEY="your-api-key"
   ```

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   Jika tidak mengakses langsung melalui *virtual host* (misal `.test` di Laragon), jalankan internal server:
   ```bash
   php artisan serve
   npm run dev
   ```

## 📜 Panduan Kontribusi / Commit

Untuk menjaga kerapian riwayat repositori, gunakan format *commit message* yang ringkas dan spesifik pada *scope* perubahan. Contoh:
- `feat(api): integrasi endpoint Lalapan Depok`
- `fix(role): pemisahan akses DAM dan Kantin per kecamatan`
- `refactor(db): rombak struktur tabel dashboard SLHS`
