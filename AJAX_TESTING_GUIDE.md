# 📋 AJAX jQuery & Axios Implementation - Testing Guide

## ✅ Implementasi Lengkap untuk Module Barang

### **1. API Routes (REST API)**
```
GET    /api/barang/all             - Ambil semua barang
POST   /api/barang/store           - Tambah barang baru
PUT    /api/barang/update/{id}     - Update barang
DELETE /api/barang/delete/{id}     - Hapus barang
```

### **2. View Pages**
- **Form HTML Table**: `/barang/form-html`
- **Form DataTables**: `/barang/form-datatable`

### **3. Features yang Tersedia**

#### ✨ Kedua Form Support:
- ✅ **Load Data** - Ambil semua barang dari API
- ✅ **Create** - Tambah barang baru via AJAX
- ✅ **Read** - Tampilkan data barang di table
- ✅ **Update** - Edit barang dengan modal
- ✅ **Delete** - Hapus barang dengan confirm

#### 🎯 UI Features:
- ✅ Modal untuk edit/delete
- ✅ Loading spinner saat proses
- ✅ Validasi form
- ✅ Format Rp untuk harga
- ✅ SweetAlert2 notifications
- ✅ Responsive table

---

## 🧪 Testing Checklist

### **Test 1: Akses Page**
- [ ] Buka `/barang/form-html` - tidak ada error?
- [ ] Buka `/barang/form-datatable` - tidak ada error?

### **Test 2: Load Data**
- [ ] Buka console (F12)
- [ ] Cek Network tab saat page load
- [ ] API call `/api/barang/all` berhasil (status 200)?
- [ ] Data barang tampil di table?

### **Test 3: Create (Tambah Data)**
- [ ] Isi form: nama barang dan harga
- [ ] Klik tombol "submit"
- [ ] Spinner muncul sebentar?
- [ ] Alert success muncul?
- [ ] Table auto-refresh dengan data baru?
- [ ] Cek Network tab: POST `/api/barang/store` berhasil?

### **Test 4: Read (Lihat Data)**
- [ ] Data tampil di table dengan format yang benar?
- [ ] Harga terformat dengan "Rp" dan separator ribuan?
- [ ] Cursor berubah ke pointer saat hover row?

### **Test 5: Update (Edit Data)**
- [ ] Klik salah satu row barang
- [ ] Modal muncul dengan data yang sesuai?
- [ ] Form modal ter-populate dengan benar?
- [ ] Edit nama atau harga
- [ ] Klik tombol "Simpan"
- [ ] Spinner muncul?
- [ ] Alert success muncul?
- [ ] Modal close dan table update?
- [ ] Cek Network tab: PUT `/api/barang/update/{id}` berhasil?

### **Test 6: Delete (Hapus Data)**
- [ ] Klik salah satu row barang
- [ ] Modal muncul
- [ ] Klik tombol "Hapus"
- [ ] Confirmation dialog muncul?
- [ ] Klik OK di confirmation
- [ ] Spinner muncul?
- [ ] Alert success muncul?
- [ ] Data hilang dari table?
- [ ] Cek Network tab: DELETE `/api/barang/delete/{id}` berhasil?

### **Test 7: Validation**
- [ ] Submit form kosong - error muncul?
- [ ] Submit dengan harga string - error muncul?
- [ ] Error message tampil dengan jelas?

### **Test 8: JavaScript Console**
- [ ] Buka F12 → Console tab
- [ ] Tidak ada error message merah?
- [ ] Tidak ada warning yang menggangu?

---

## 📝 File-file yang Dibuat/Diubah

### **Controller**
- ✅ `app/Http/Controllers/BarangController.php` - 4 method AJAX baru

### **Routes**
- ✅ `routes/web.php` - 4 API routes baru

### **JavaScript**
- ✅ `public/assets/js/form-html.js` - AJAX handler untuk HTML table
- ✅ `public/assets/js/form-datatable.js` - AJAX handler untuk DataTables

### **Views**
- ✅ `resources/views/barang/form-html.blade.php` - Updated dengan script
- ✅ `resources/views/barang/form-datatable.blade.php` - Updated dengan script

### **Layout**
- ✅ `resources/views/layouts/app.blade.php` - Added Axios & SweetAlert2

---

## 🔧 Troubleshooting

### Jika CSRF Token Error:
```
POST 419 Unauthenticated
```
→ Pastikan layout sudah include Axios setup dengan CSRF token ✅

### Jika API Endpoint Error:
```
GET 404 Not Found
```
→ Run: `php artisan route:list` cek routes terdaftar ✅

### Jika Data Tidak Load:
```
GET 500 Internal Server Error
```
→ Cek: DB connection & migration sudah jalan ✅

### Jika JavaScript Error:
```
ReferenceError: axios is not defined
```
→ Pastikan Axios CDN loaded sebelum form-html.js / form-datatable.js ✅

---

## 📊 Curl Testing (Optional)

### Get All Barang:
```bash
curl -H "Accept: application/json" http://localhost/api/barang/all
```

### Create Barang:
```bash
curl -X POST http://localhost/api/barang/store \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_TOKEN" \
  -d '{"nama_barang":"Test","harga":100000}'
```

---

## ✨ Implementation Summary

| Aspek | Status | Keterangan |
|-------|--------|-----------|
| Routes | ✅ | 4 API endpoints siap |
| Controller Methods | ✅ | 4 methods dengan error handling |
| HTML Form | ✅ | Button & form sudah siap |
| JavaScript - HTML | ✅ | AJAX handlers lengkap |
| JavaScript - DataTables | ✅ | DataTables integration ready |
| Axios Setup | ✅ | CSRF token configured |
| Validation | ✅ | Server-side & client-side |
| Error Handling | ✅ | Try-catch & validasi |
| UI/UX | ✅ | Loading spinners & alerts |

---

**👤 Created by:** GitHub Copilot  
**📅 Date:** March 12, 2026  
**🎯 Module:** AJAX jQuery dan Axios
