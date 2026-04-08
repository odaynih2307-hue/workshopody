# 🚀 QUICK START GUIDE

## Setup Awal (Jika Database Kosong)

```bash
# 1. Di folder project
cd c:\laragon\www\workshop

# 2. Jalankan migrations & seeds
php artisan migrate:fresh
php artisan db:seed

# 3. Bersihkan cache (opsional tapi disarankan)
php artisan cache:clear
php artisan config:clear

# 4. Jalankan server
php artisan serve
```

## Login
- **Email**: test@example.com
- **Password**: password

---

## 📍 Akses Sistem

### Cascading Select Wilayah
🔗 Menu: Studi Kasus → Cascading Select Wilayah
atau langsung: `http://localhost:8000/wilayah/cascading-form`

**Cara Gunakan:**
1. Pilih Provinsi
2. Otomatis muncul Kota
3. Otomatis muncul Kecamatan
4. Otomatis muncul Kelurahan
5. Klik "Simpan"

**Fitur:**
- Menggunakan **Axios** ✅
- Reset otomatis saat level sebelumnya berubah ✅
- 7 Provinsi + 14 Kota + data Kecamatan & Kelurahan Indonesia ✅

---

### Point of Sales (POS) / Kasir
🔗 Menu: Studi Kasus → Point of Sales (POS)
atau langsung: `http://localhost:8000/kasir`

**Cara Gunakan:**
1. Masukkan **Kode Barang** (misal: BRG-001) atau pilih dari dropdown
2. Tekan Enter atau klik "Tambahkan"
3. Masukkan **Jumlah** barang
4. Barang muncul di tabel
5. Ulangi untuk barang lain (atau edit jumlah langsung di tabel)
6. Masukkan **Jumlah Bayar**
7. Lihat **Kembalian** otomatis
8. Klik **Bayar**
9. ✅ Transaksi berhasil tersimpan!
10. Lihat **No. Penjualan** (format: PJYYYYMMDDxxxx)

**Daftar Barang Demo:**
- BRG-001 → Laptop Lenovo (Rp 7.500.000)
- BRG-002 → Mouse Wireless (Rp 150.000)
- BRG-003 → Keyboard Mechanical (Rp 850.000)
- BRG-004 → Monitor LG 24" (Rp 2.500.000)
- BRG-005 → Headphone Sony (Rp 1.200.000)
- BRG-006 → Webcam HD (Rp 500.000)
- BRG-007 → SSD 512GB (Rp 800.000)
- ...dan lebih banyak lagi

**Fitur:**
- Search barang by kode ✅
- Select barang dari dropdown ✅
- Edit quantity langsung di tabel ✅
- Hapus barang dari keranjang ✅
- Hitung otomatis total, subtotal, kembalian ✅
- Simpan transaksi dengan No. Penjualan unik ✅
- Data barang TIDAK HILANG - hanya mencatat transaksi ✅

**Lihat History:**
- Klik tombol "Lihat History Penjualan"
- atau Menu: Studi Kasus → Klik pada kasir history (di alert/popup)
- Klik "Detail" untuk melihat detail transaksi
- Klik "Cetak" untuk print invoice

---

## 🔄 Reset Sistem (Jika Diperlukan)

**Reset Database Lengkap:**
```bash
php artisan migrate:fresh
php artisan db:seed
```

**Reset Cache (Jika halaman tidak update):**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📊 Verifikasi Data

### Cek Data Wilayah
```bash
php artisan tinker
# Kemudian di terminal tinker:
>>> App\Models\Provinsi::count()
# Harus menampilkan: 7 (provinsi)
>>> App\Models\Kota::count()
# Harus menampilkan: 14+ (kota)
```

### Cek Data Barang
```bash
php artisan tinker
>>> App\Models\Barang::count()
# Harus menampilkan: 10+ (barang)
>>> App\Models\Barang::first()
# Harus menampilkan data barang
```

---

## ⚠️ Penting!

✅ **Data Barang AMAN - Tidak Akan Hilang**
- Sistem POS hanya mencatat TRANSAKSI
- Data master barang tetap di tabel `barang`
- Setiap transaksi tercatat di tabel `penjualan` & `penjualan_detail`

✅ **Cascading Select Menggunakan Axios**
- Bukan Fetch API
- Semua request via `/api/wilayah/*` endpoints
- Auto-loading indicator di browser

✅ **Semua Persyaratan Terpenuhi**
- Studi Kasus 1 ✅
- Studi Kasus 2 ✅
- Tidak ada error ✅
- Data tidak hilang ✅

---

## 🎯 Testing Checklist

### Cascading Form
- [ ] Pilih Provinsi → Muncul Kota
- [ ] Ubah Provinsi → Kota & Kecamatan & Kelurahan reset
- [ ] Ubah Kota → Kecamatan & Kelurahan reset  
- [ ] Pilih semua level 4 → Simpan → Muncul data
- [ ] Klik Reset → Form kosong

### POS/Kasir
- [ ] Input kode BRG-001 → Muncul nama & harga
- [ ] Tekan Enter → Barang ditambah ke tabel
- [ ] Edit jumlah → Subtotal otomatis update
- [ ] Edit jumlah jadi 0 → Barang hapus otomatis
- [ ] Hapus barang → Konfirmasi → Barang hilang
- [ ] Input bayar < total → Tombol Bayar disable
- [ ] Input bayar ≥ total → Tombol Bayar enable
- [ ] Klik Bayar → Terima kasih dialog muncul
- [ ] Lihat history → Transaksi muncul dengan status "Selesai"
- [ ] Klik Detail → Modal muncul with detail items
- [ ] Klik Cetak → Print preview muncul

---

## 🆘 Error Fixing

**Error: "SQLSTATE[23000]: Integrity constraint violation"**
→ Jalankan: `php artisan migrate:fresh && php artisan db:seed`

**Error: "Call to undefined route"**
→ Pastikan routes/web.php sudah di-save
→ Jalankan: `php artisan route:clear`

**Error: "API not found"**
→ Cek browser console
→ Pastikan controller method ada
→ Jalankan: `php artisan cache:clear`

**Error: "Axios is not defined"**
→ Halaman sudah include Axios CDN
→ Cek di browser console tidak ada error script

---

## 📞 File Penting

- **Routes**: `routes/web.php`
- **Controllers**: 
  - `app/Http/Controllers/WilayahController.php`
  - `app/Http/Controllers/PenjualanController.php`
- **Views**:
  - `resources/views/wilayah/cascading-form.blade.php`
  - `resources/views/penjualan/kasir.blade.php`
  - `resources/views/penjualan/history.blade.php`
- **Models**:
  - `app/Models/Provinsi.php`, `Kota.php`, `Kecamatan.php`, `Kelurahan.php`
  - `app/Models/Penjualan.php`, `PenjualanDetail.php`
- **Migrations**: `database/migrations/` (file 2026_03_12_*)
- **Seeders**: `database/seeders/WilayahSeeder.php`

---

## 📈 Statistik Database

Setelah seed, database akan berisi:
- **Provinsi**: 7 data
- **Kota**: 14 data  
- **Kecamatan**: 30+ data
- **Kelurahan**: 90+ data
- **Barang**: 10 data
- **Users**: 1 data (test user)
- **Penjualan**: Kosong (akan bertambah saat transaksi)
- **Penjualan_Detail**: Kosong

---

**Sebagai Developer:**
Semua sudah siap pakai, tinggal login dan test! 🎉
