## 🟩 1. **Pengunjung Publik (Tanpa Login)**

### 1.1. Akses Informasi

- Melihat halaman:

  - Beranda
  - Tentang Yayasan
  - Program Unggulan
  - Daftar Fundraiser Aktif
  - Paket Donasi Rutin
  - Kegiatan Sosial (Activities)

### 1.2. Donasi Tanpa Login

- Mengisi form donasi dengan:

  - Nama
  - Email (opsional)
  - Nomor WA
  - Pilihan kategori / paket / fundraiser
  - Nominal donasi

- Sistem menyimpan dengan status `pending`
- Setelah submit:

  - Tampil instruksi transfer manual
  - Tersedia QR Code / No. Rek

- Menunggu verifikasi dari admin

---

## 🟨 2. **Donatur Terdaftar (Login)**

### 2.1. Registrasi dan Login

- Pengguna mendaftar dan memilih peran default (misal: `donor`)
- Login menggunakan email & password

### 2.2. Akses Tambahan

- Melakukan donasi seperti publik, tapi:

  - Data donasi otomatis terhubung ke akun
  - Bisa melihat **riwayat donasi**:

    - Status (pending, confirmed, cancelled)
    - Jumlah total donasi
    - Rincian donasi terhadap fundraiser/paket

- Dapat mencetak / unduh nota donasi (jika disediakan)

---

## 🟦 3. **Admin / Superadmin**

### 3.1. Manajemen Konten

- **Programs**: hanya superadmin yang bisa CRUD
- **Donation Packages**: tambah/edit/nonaktifkan
- **Fundraisers**:

  - Tambah fundraiser baru
  - Update target, deadline, status
  - Upload gambar
  - Atur status `is_published`

- **Activities**: CRUD kegiatan sosial atau internal
- **Users**: melihat semua user dan mengatur role
- **Roles**: CRUD role (`admin`, `donor`, `editor`, dsb.)

### 3.2. Donasi

- Melihat semua donasi masuk
- Verifikasi donasi manual:

  - Ubah status ke `confirmed`
  - Tambahkan `confirmation_note`
  - Isi `confirmed_at`

- Menambahkan donasi manual (misal dari donasi offline)

### 3.3. Statistik & Laporan

- Melihat dashboard donasi per hari/bulan
- Laporan donasi per fundraiser atau paket
- (Opsional) Export CSV / Excel

---

## 🟧 4. **Editor / Fundraiser Manager**

### 4.1. Fundraiser

- Membuat fundraiser (hanya untuk dirinya)
- Mengelola fundraiser:

  - Deskripsi
  - Target & progress manual (jika ada donasi offline)
  - Deadline
  - Gambar dan publikasi

### 4.2. Activities

- Membuat dan mengedit kegiatan yang dilaksanakan
- Mengatur apakah ingin dipublikasikan atau tidak

---

## 🟥 5. **Donasi Non-Web (Manual Entry oleh Admin)**

### 5.1. Transfer Bank Langsung

- Donatur transfer langsung ke rekening yayasan
- Kirim bukti via WA atau formulir
- Admin input ke sistem dengan status `confirmed` atau `pending`
- Dapat dikaitkan ke fundraiser tertentu

### 5.2. Donasi Saat Event

- Panitia mencatat donasi offline (tunai/QRIS)
- Admin input total ke sistem dan progress fundraiser diperbarui

---

## 🔷 6. **Fitur Sistem Tambahan (Opsional Masa Depan)**

### 6.1. Notifikasi

- Email notifikasi saat donasi berhasil atau diverifikasi

### 6.2. Dashboard Umum

- Total donasi
- Jumlah fundraiser aktif
- Jumlah pengguna

### 6.3. Keamanan & Audit

- Logging perubahan data
- Riwayat update oleh siapa (untuk `activities` dan `fundraisers`)

### 6.4. Pengingat Internal

- Fundraiser mendekati deadline → notifikasi internal

---

## 🔶 7. **Edge Case / Kebutuhan Spesifik**

- Donasi tanpa memilih paket/fundraiser → `title` digunakan untuk mendeskripsikan.
- Admin menonaktifkan sementara konten via `is_published` / `is_active`.
- Donatur bisa login & klaim donasi sebelumnya jika data cocok (email/WA).
- Tidak ada integrasi payment gateway → verifikasi tetap bisa dilakukan manual/semi-otomatis.
