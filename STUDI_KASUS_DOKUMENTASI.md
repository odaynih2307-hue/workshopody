# 📋 DOKUMENTASI SISTEM - STUDI KASUS

## Daftar Isi
1. [Studi Kasus 1: Cascading Select Wilayah](#studi-kasus-1-cascading-select-wilayah)
2. [Studi Kasus 2: Sistem POS/Kasir](#studi-kasus-2-sistem-poskasir)
3. [API Endpoints](#api-endpoints)
4. [Database Structure](#database-structure)
5. [Cara Menggunakan](#cara-menggunakan)

---

## 🎯 Studi Kasus 1: Cascading Select Wilayah

### Deskripsi
Sistem cascading select untuk memilih wilayah administrasi Indonesia dengan 4 level:
- **Level 1**: Provinsi
- **Level 2**: Kota (bergantung Provinsi)
- **Level 3**: Kecamatan (bergantung Kota)
- **Level 4**: Kelurahan (bergantung Kecamatan)

### Fitur Utama
✅ **Axios Integration**: Menggunakan Axios untuk komunikasi AJAX
✅ **Cascading Logic**: Setiap level bergantung pada level sebelumnya
✅ **Auto-Reset**: Ketika level sebelumnya berubah, level selanjutnya direset
✅ **Dynamic Loading**: Data dimuat dari API tanpa page refresh
✅ **Validasi Input**: Validasi di client-side juga
✅ **Display Data**: Menampilkan data yang dipilih dalam bentuk tabel

### Persyaratan Poin-Poin Studi Kasus
- ✅ **Poin A**: 4 rangkaian komponen select (Provinsi, Kota, Kecamatan, Kelurahan)
- ✅ **Poin B**: Perhitungan urutan level (Level 1-4)
- ✅ **Poin C**: Dipilih dari nilai yang terpilih sebelumnya
- ✅ **Poin D**: Reset level 3 & 4 ketika level 1 berubah
- ✅ **Poin E**: Reset level 4 ketika level 2 berubah
- ✅ **Poin F**: Placeholder options pada setiap select
- ✅ **Poin G**: Event untuk trigger AJAX (onChange)
- ✅ **Poin H**: Menggunakan Axios

### URL
```
http://localhost/workshop/wilayah/cascading-form
```

### Struktur Database
```
Provinsi
├── id (primary key)
└── nama_provinsi

Kota
├── id (primary key)
├── provinsi_id (foreign key → Provinsi)
└── nama_kota

Kecamatan
├── id (primary key)
├── kota_id (foreign key → Kota)
└── nama_kecamatan

Kelurahan
├── id (primary key)
├── kecamatan_id (foreign key → Kecamatan)
└── nama_kelurahan
```

### Data Sample
Sistem sudah di-seed dengan data Indonesia:
- 7 Provinsi
- 14 Kota
- Multiple Kecamatan dan Kelurahan untuk setiap Kota

---

## 🛒 Studi Kasus 2: Sistem POS/Kasir

### Deskripsi
Sistem Point of Sales (POS) atau halaman kasir untuk mencatat transaksi penjualan barang.

### Fitur Utama
✅ **Input Barang**: Cari barang berdasarkan kode atau pilih dari dropdown
✅ **Keranjang Belanja**: Tampilkan daftar barang yang dibeli
✅ **Edit Quantity**: Ubah jumlah barang langsung di tabel
✅ **Hapus Barang**: Hapus barang dari keranjang
✅ **Penghitungan Otomatis**: Total harga, jumlah barang, dan kembalian otomatis
✅ **Pembayaran**: Input jumlah bayar dan hitung kembalian
✅ **Simpan Transaksi**: Simpan ke database dengan nomor penjualan unik
✅ **History**: Lihat riwayat semua transaksi
✅ **Detail Transaksi**: Lihat detail dan cetak invoice

### URL
```
Halaman Kasir: http://localhost/workshop/kasir
History: http://localhost/workshop/kasir/history
```

### Struktur Database
```
Penjualan
├── id (primary key)
├── no_penjualan (unique)
├── user_id (foreign key → Users)
├── tanggal_penjualan
├── total_jumlah
├── total_harga
├── jumlah_bayar
├── kembalian
└── status (selesai/batal)

Penjualan_Detail
├── id (primary key)
├── penjualan_id (foreign key → Penjualan)
├── barang_id (foreign key → Barang)
├── jumlah
├── harga
└── subtotal
```

### Data Barang
Sistem sudah memiliki data barang:
- BRG-001: Laptop Lenovo (Rp 7.500.000)
- BRG-002: Mouse Wireless (Rp 150.000)
- BRG-003: Keyboard Mechanical (Rp 850.000)
- BRG-004: Monitor LG 24" (Rp 2.500.000)
- BRG-005: Headphone Sony (Rp 1.200.000)
- BRG-006: Webcam HD (Rp 500.000)
- BRG-007: SSD 512GB (Rp 800.000)
- Dan lebih banyak lagi...

### Nomor Penjualan Format
```
PJ<YYYYMMDD><5-digit-sequence>
Contoh: PJ202603120001
```

---

## 📡 API Endpoints

### Wilayah (Cascading)
```
GET  /api/wilayah/provinsi                        - Get semua Provinsi
GET  /api/wilayah/kota/{provinsi_id}              - Get Kota by Provinsi
GET  /api/wilayah/kecamatan/{kota_id}             - Get Kecamatan by Kota
GET  /api/wilayah/kelurahan/{kecamatan_id}        - Get Kelurahan by Kecamatan
```

### Penjualan (POS)
```
GET  /api/penjualan/all-barang                    - Get semua Barang
GET  /api/penjualan/search/{kode}                 - Search Barang by Kode
POST /api/penjualan/save                          - Save Penjualan
GET  /api/penjualan/history                       - Get History Penjualan
```

---

## 🗄️ Database Structure

### Entity Relationship Diagram (ERD)
```
┌─────────────────┐
│   Provinsi      │
│  ┌───────────┐  │
│  │ id  (PK)  │  │
│  │ nama      │  │
│  └───────────┘  │
└────────┬────────┘
         │ 1:N
         ▼
┌─────────────────┐
│     Kota        │
│  ┌───────────┐  │
│  │ id  (PK)  │  │
│  │ provinsi  │  │◄─── nama (untuk backward compatibility)
│  │ provinsi_ │  │
│  │ id (FK)   │  │
│  │ nama_kota │  │
│  └───────────┘  │
└────────┬────────┘
         │ 1:N
         ▼
┌──────────────────┐
│   Kecamatan      │
│  ┌────────────┐  │
│  │ id   (PK)  │  │
│  │ kota_id    │  │
│  │ (FK)       │  │
│  │ nama_      │  │
│  │ kecamatan  │  │
│  └────────────┘  │
└────────┬─────────┘
         │ 1:N
         ▼
┌──────────────────┐
│   Kelurahan      │
│  ┌────────────┐  │
│  │ id   (PK)  │  │
│  │ kecamatan_ │  │
│  │ id (FK)    │  │
│  │ nama_      │  │
│  │ kelurahan  │  │
│  └────────────┘  │
└──────────────────┘

┌──────────────────┐
│      Barang      │
│  ┌────────────┐  │
│  │ id   (PK)  │  │
│  │ id_barang  │  │◄─── unique identifier
│  │ nama_      │  │
│  │ barang     │  │
│  │ harga      │  │
│  └────────────┘  │
└────────┬─────────┘
         │ 1:N
         ▼
┌───────────────────────────┐      ┌─────────────────┐
│    Penjualan_Detail       │◄──────│   Penjualan     │
│  ┌─────────────────────┐  │      │  ┌───────────┐  │
│  │ id            (PK)  │  │      │  │ id  (PK)  │  │
│  │ penjualan_id  (FK)  │  │      │  │ no_       │  │
│  │ barang_id     (FK)  │  │      │  │ penjualan │  │
│  │ jumlah              │  │      │  │ user_id   │  │
│  │ harga               │  │      │  │ tanggal   │  │
│  │ subtotal            │  │      │  │ total_...│  │
│  └─────────────────────┘  │      │  └───────────┘  │
└───────────────────────────┘      └─────────────────┘
```

---

## 💻 Cara Menggunakan

### Cascading Select Wilayah

#### Langkah Penggunaan:
1. **Login** ke sistem dengan user test atau user yang sudah ada
2. **Buka** menu "Studi Kasus" → "Cascading Select Wilayah"
3. **Pilih Provinsi** dari dropdown pertama
4. **Pilih Kota** - dropdown akan terisi otomatis sesuai provinsi yang dipilih
5. **Pilih Kecamatan** - dropdown akan terisi sesuai kota yang dipilih
6. **Pilih Kelurahan** - dropdown akan terisi sesuai kecamatan yang dipilih
7. **Klik Simpan** untuk menyimpan pilihan (data ditampilkan di bawah form)
8. **Klik Reset** untuk menghapus semua pilihan

#### Catatan:
- ⚠️ Jika mengubah Provinsi, Kecamatan dan Kelurahan akan direset otomatis
- ⚠️ Jika mengubah Kota, Kelurahan akan direset otomatis
- ℹ️ Semua data dimuat via AJAX tanpa page refresh
- ℹ️ Menggunakan **Axios** untuk komunikasi dengan API

---

### Sistem POS/Kasir

#### Langkah Penggunaan:

**Menambah Barang:**
1. **Metode 1 - Input Kode Barang**:
   - Masukkan Kode Barang (misal: BRG-001)
   - Tekan Enter atau klik Tambahkan
   - Sistem akan mencari barang di database
   - Input Jumlah barang yang ingin dibeli
   - Klik Tambahkan

2. **Metode 2 - Pilih dari Dropdown**:
   - Pilih barang dari dropdown "Atau Pilih dari Dropdown"
   - Form akan terisi otomatis
   - Input Jumlah barang
   - Klik Tambahkan

**Mengelola Keranjang:**
- **Edit Jumlah**: Ubah langsung di kolom Jumlah di tabel
- **Hapus Barang**: Klik tombol Hapus (ikon trash), akan konfirmasi
- **Reset Keranjang**: Klik tombol Reset untuk menghapus semua barang

**Pembayaran:**
1. Lihat **Total Harga** di bagian bawah
2. Masukkan **Jumlah Bayar** (harus ≥ Total Harga)
3. **Kembalian** akan dihitung otomatis
4. Klik **Bayar** untuk menyimpan transaksi

**Setelah Transaksi:**
- Sistem akan menampilkan **No. Penjualan** unik
- Detail transaksi akan muncul di alert
- Keranjang akan direset otomatis
- Transaksi tersimpan di database

**Melihat History:**
1. Klik menu "Studi Kasus" → "Point of Sales (POS)"
2. Atau klik tombol "Lihat History Penjualan"
3. Semua transaksi akan ditampilkan dalam tabel
4. Klik **Detail** untuk melihat detail transaksi
5. Klik **Cetak** untuk print invoice

---

## 🔧 Installation & Setup

### Requirements
- PHP 8.0+
- Laravel 11
- MySQL 5.7+
- Composer
- Axios (via CDN, sudah di-include)

### Step-by-Step Setup

1. **Buat Database**
```bash
# Masuk ke MySQL
mysql -u root -p
CREATE DATABASE koleksi_buku;
```

2. **Update .env**
```
DB_DATABASE=koleksi_buku
DB_USERNAME=root
DB_PASSWORD=
```

3. **Run Migrations**
```bash
php artisan migrate
```

4. **Run Seeders**
```bash
php artisan db:seed
```

5. **Start Laravel Server**
```bash
php artisan serve
```

6. **Login**
- Email: test@example.com
- Password: password

---

## ✅ Checklist Persyaratan Studi Kasus

### Studi Kasus 1: Cascading Select Wilayah
- ✅ 4 rangkaian komponen select (Provinsi, Kota, Kecamatan, Kelurahan)
- ✅ Perhitungan level (1-4) sesuai hirarki
- ✅ Pilihan bergantung pada level sebelumnya
- ✅ Reset level 3 & 4 saat level 1 berubah
- ✅ Reset level 4 saat level 2 berubah
- ✅ Placeholder options: "Pilih [Wilayah]"
- ✅ Event trigger menggunakan onChange
- ✅ Menggunakan Axios untuk AJAX
- ✅ Data sample Indonesia included

### Studi Kasus 2: POS/Kasir
- ✅ Halaman POS/Kasir dengan UI lengkap
- ✅ Form input barang (kode, nama, harga, jumlah)
- ✅ Tombol "Tambahkan"
- ✅ Tabel dengan kolom: Kode, Nama, Harga, Jumlah, Subtotal
- ✅ Display Total dan Tombol "Bayar"
- ✅ Search barang by kode
- ✅ Dropdown select barang
- ✅ Edit/Hapus barang di keranjang
- ✅ Penghitungan otomatis subtotal dan total
- ✅ Penghitungan kembalian
- ✅ Simpan transaksi ke database
- ✅ Generate no_penjualan otomatis
- ✅ History penjualan
- ✅ Detail & Print invoice
- ✅ **Data Barang TETAP, TIDAK HILANG** - Sistem hanya mencatat transaksi
- ✅ Tidak ada data yang hilang/terhapus

---

## 🐛 Troubleshooting

### Masalah: Dropdown tidak menampilkan data
**Solusi:**
1. Cek browser console untuk error
2. Pastikan API endpoints accessible
3. Jalankan `php artisan cache:clear`
4. Jalankan `php artisan config:clear`

### Masalah: Barang tidak ditemukan saat search
**Solusi:**
1. Pastikan kode barang sesuai (BRG-001, dll)
2. Cek database apakah sudah di-seed
3. Contoh kode valid: BRG-001, BRG-002, dst.

### Masalah: Transaksi tidak tersimpan
**Solusi:**
1. Pastikan sudah login
2. Cek minimal 1 barang di keranjang
3. Pastikan jumlah bayar ≥ total harga
4. Lihat console browser untuk error detail

### Masalah: Migrasi gagal
**Solusi:**
1. Jalankan `php artisan migrate:fresh` (WARNING: akan hapus semua data)
2. Pastikan database connection di .env benar
3. Cek MySQL sudah running

---

## 📝 Notes

- Sistem ini tidak menghapus data barang, hanya mencatat transaksi
- Setiap transaksi mendapat nomor unik: PJ + YYYYMMDD + sequence
- Data Provinsi, Kota, Kecamatan, Kelurahan adalah data sample
- Semua AJAX menggunakan Axios, bukan Fetch API
- Struktur database sudah normalized (3NF)

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan:
1. Cek file log di `storage/logs/`
2. Enable debugging di `.env`: `APP_DEBUG=true`
3. Lihat error detail di browser console (F12)

---

**Dibuat dengan ❤️ untuk memenuhi persyaratan Studi Kasus**
