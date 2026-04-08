@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Point Of Sales (POS) - Halaman Kasir</h2>
            <p class="text-muted">Studi Kasus: Sistem Kasir dengan Data Barang</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('kasir.history') }}" class="btn btn-info">Lihat History Penjualan</a>
        </div>
    </div>

    <div class="row">
        <!-- Input Barang -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Input Barang</h5>
                </div>
                <div class="card-body">
                    <form id="inputBarangForm">
                        <div class="mb-3">
                            <label for="kodeBarang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kodeBarang" placeholder="Masukkan kode barang" required>
                            <small class="text-muted">Tekan Enter untuk search</small>
                        </div>

                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="namaBarang" placeholder="Nama barang" readonly style="background-color: #e9ecef;">
                        </div>

                        <div class="mb-3">
                            <label for="hargaBarang" class="form-label">Harga Barang</label>
                            <input type="number" class="form-control" id="hargaBarang" placeholder="Harga" readonly style="background-color: #e9ecef;">
                        </div>

                        <div class="mb-3">
                            <label for="jumlahBarang" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlahBarang" value="1" min="1" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" id="tambahButton" onclick="tambahBarang()">
                                <i class="fas fa-plus"></i> Tambahkan
                            </button>
                        </div>
                    </form>

                    <!-- Alternative: Dropdown Select -->
                    <hr class="my-4">
                    <h6>Atau Pilih dari Dropdown:</h6>
                    <select class="form-select" id="barangSelect" onchange="pilihBarangDariSelect()">
                        <option value="">Pilih Barang</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Keranjang & Total -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Barang</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <div id="emptyCart" class="alert alert-info text-center">
                        <p>Keranjang belanja kosong</p>
                    </div>
                    <table id="cartTable" class="table table-hover" style="display: none;">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th style="width: 80px;">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                        </tbody>
                    </table>
                </div>

                <!-- Total Section -->
                <div class="card-footer bg-light">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Total Jumlah Barang:</h6>
                            <h4><span id="totalJumlah">0</span> Barang</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>Total Harga:</h6>
                            <h4>Rp <span id="totalHarga">0</span></h4>
                        </div>
                    </div>

                    <!-- Pembayaran Section -->
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="jumlahBayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg" id="jumlahBayar" placeholder="Masukkan jumlah bayar" onchange="hitungKembalian()" oninput="hitungKembalian()">
                        </div>
                        <div class="col-md-6">
                            <label for="kembalian" class="form-label">Kembalian</label>
                            <input type="number" class="form-control form-control-lg" id="kembalian" value="0" readonly style="background-color: #e9ecef;">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="button" class="btn btn-success btn-lg" id="bayarButton" onclick="prosesTransaksi()" disabled>
                            <i class="fas fa-money-bill-wave"></i> Bayar
                        </button>
                        <button type="button" class="btn btn-danger" id="resetButton" onclick="resetKeranjang()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Global cart array
    let cart = [];
    let barangData = {};

    // Load barang on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadBarangDropdown();
        
        // Event: Enter pada input kode barang
        document.getElementById('kodeBarang').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBarang();
            }
        });
    });

    /**
     * Load semua barang untuk dropdown
     */
    function loadBarangDropdown() {
        axios.get('{{ route("penjualan.api.all-barang") }}')
            .then(response => {
                if (response.data.status) {
                    const barangSelect = document.getElementById('barangSelect');
                    barangSelect.innerHTML = '<option value="">Pilih Barang</option>';
                    
                    response.data.data.forEach(item => {
                        barangData[item.id] = item;
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.id_barang} - ${item.nama_barang} (Rp ${formatRupiah(item.harga)})`;
                        barangSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    /**
     * Search Barang berdasarkan kode
     */
    function searchBarang() {
        const kode = document.getElementById('kodeBarang').value.trim();
        
        if (!kode) {
            showAlert('Masukkan kode barang terlebih dahulu', 'warning');
            return;
        }

        axios.get(`{{ url('api/penjualan/search') }}/${kode}`)
            .then(response => {
                if (response.data.status) {
                    const barang = response.data.data;
                    document.getElementById('namaBarang').value = barang.nama_barang;
                    document.getElementById('hargaBarang').value = barang.harga;
                    document.getElementById('jumlahBarang').value = 1;
                    barangData[barang.id] = barang;
                    
                    // Focus ke jumlah untuk input cepat
                    document.getElementById('jumlahBarang').focus();
                    document.getElementById('jumlahBarang').select();
                } else {
                    showAlert('Barang tidak ditemukan', 'danger');
                    clearInputBarang();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
                clearInputBarang();
            });
    }

    /**
     * Pilih barang dari dropdown
     */
    function pilihBarangDariSelect() {
        const barangId = document.getElementById('barangSelect').value;
        
        if (!barangId) {
            clearInputBarang();
            return;
        }

        const barang = barangData[barangId];
        if (barang) {
            document.getElementById('kodeBarang').value = barang.id_barang;
            document.getElementById('namaBarang').value = barang.nama_barang;
            document.getElementById('hargaBarang').value = barang.harga;
            document.getElementById('jumlahBarang').value = 1;
            document.getElementById('jumlahBarang').focus();
        }
    }

    /**
     * Tambah Barang ke Keranjang
     */
    function tambahBarang() {
        const kode = document.getElementById('kodeBarang').value.trim();
        const nama = document.getElementById('namaBarang').value.trim();
        const harga = parseInt(document.getElementById('hargaBarang').value);
        const jumlah = parseInt(document.getElementById('jumlahBarang').value);

        if (!kode || !nama || !harga || !jumlah) {
            showAlert('Silahkan lengkapi input barang terlebih dahulu', 'warning');
            return;
        }

        if (jumlah <= 0) {
            showAlert('Jumlah harus lebih dari 0', 'warning');
            return;
        }

        // Cek apakah barang sudah ada di cart
        const existingItem = cart.find(item => item.id_barang === kode);
        
        if (existingItem) {
            existingItem.jumlah += jumlah;
            existingItem.subtotal = existingItem.jumlah * existingItem.harga;
        } else {
            // Dapatkan barang_id dari barangData
            let barang_id = null;
            for (let id in barangData) {
                if (barangData[id].id_barang === kode) {
                    barang_id = id;
                    break;
                }
            }

            if (!barang_id) {
                showAlert('Data barang tidak ditemukan di sistem', 'danger');
                return;
            }

            cart.push({
                barang_id: barang_id,
                id_barang: kode,
                nama_barang: nama,
                harga: harga,
                jumlah: jumlah,
                subtotal: jumlah * harga
            });
        }

        updateCartDisplay();
        clearInputBarang();
        showAlert('Barang berhasil ditambahkan', 'success');
    }

    /**
     * Update tampilan keranjang
     */
    function updateCartDisplay() {
        const emptyCart = document.getElementById('emptyCart');
        const cartTable = document.getElementById('cartTable');
        const cartBody = document.getElementById('cartBody');

        if (cart.length === 0) {
            emptyCart.style.display = 'block';
            cartTable.style.display = 'none';
            document.getElementById('bayarButton').disabled = true;
            clearTotals();
            return;
        }

        emptyCart.style.display = 'none';
        cartTable.style.display = 'table';
        document.getElementById('bayarButton').disabled = false;

        cartBody.innerHTML = '';
        let totalJumlah = 0;
        let totalHarga = 0;

        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.id_barang}</td>
                <td>${item.nama_barang}</td>
                <td>Rp ${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1" onchange="updateJumlah(${index}, this.value)">
                </td>
                <td class="text-end">Rp ${formatRupiah(item.subtotal)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusBarang(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            cartBody.appendChild(row);

            totalJumlah += item.jumlah;
            totalHarga += item.subtotal;
        });

        document.getElementById('totalJumlah').textContent = totalJumlah;
        document.getElementById('totalHarga').textContent = formatRupiah(totalHarga);
        document.getElementById('jumlahBayar').value = '';
        document.getElementById('kembalian').value = '0';
    }

    /**
     * Update jumlah barang di cart
     */
    function updateJumlah(index, jumlah) {
        const newJumlah = parseInt(jumlah);
        if (newJumlah <= 0) {
            hapusBarang(index);
            return;
        }
        
        cart[index].jumlah = newJumlah;
        cart[index].subtotal = newJumlah * cart[index].harga;
        updateCartDisplay();
    }

    /**
     * Hapus barang dari cart
     */
    function hapusBarang(index) {
        if (confirm('Apakah anda yakin ingin menghapus barang ini?')) {
            cart.splice(index, 1);
            updateCartDisplay();
            showAlert('Barang berhasil dihapus', 'info');
        }
    }

    /**
     * Hitung kembalian
     */
    function hitungKembalian() {
        const totalHarga = parseInt(document.getElementById('totalHarga').textContent.replace(/\D/g, ''));
        const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
        const kembalian = jumlahBayar - totalHarga;

        if (kembalian < 0) {
            document.getElementById('kembalian').value = '0';
            document.getElementById('bayarButton').disabled = true;
        } else {
            document.getElementById('kembalian').value = kembalian;
            document.getElementById('bayarButton').disabled = false;
        }
    }

    /**
     * Proses Transaksi / Bayar
     */
    function prosesTransaksi() {
        if (cart.length === 0) {
            showAlert('Keranjang belanja kosong', 'warning');
            return;
        }

        const totalJumlah = parseInt(document.getElementById('totalJumlah').textContent);
        const totalHarga = parseInt(document.getElementById('totalHarga').textContent.replace(/\D/g, ''));
        const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value);

        if (!jumlahBayar || jumlahBayar < totalHarga) {
            showAlert('Jumlah pembayaran tidak valid atau kurang', 'danger');
            return;
        }

        const button = document.getElementById('bayarButton');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        axios.post('{{ route("penjualan.api.save") }}', {
            total_jumlah: totalJumlah,
            total_harga: totalHarga,
            jumlah_bayar: jumlahBayar,
            items: cart
        })
        .then(response => {
            if (response.data.status) {
                showAlert(`Transaksi berhasil! No. Penjualan: ${response.data.data.no_penjualan}`, 'success');
                
                // Tampilkan detail transaksi
                const kembalian = response.data.data.kembalian;
                alert(`
📋 DETAIL TRANSAKSI
═══════════════════════════
No. Penjualan: ${response.data.data.no_penjualan}
Total Barang: ${totalJumlah}
Total Harga: Rp ${formatRupiah(totalHarga)}
Jumlah Bayar: Rp ${formatRupiah(jumlahBayar)}
Kembalian: Rp ${formatRupiah(kembalian)}
═══════════════════════════
Terima kasih atas pembelian Anda!
                `);
                
                resetKeranjang();
            } else {
                showAlert('Gagal memproses transaksi: ' + response.data.message, 'danger');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-money-bill-wave"></i> Bayar';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = error.response?.data?.message || error.message;
            showAlert('Error: ' + errorMsg, 'danger');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-money-bill-wave"></i> Bayar';
        });
    }

    /**
     * Reset Keranjang
     */
    function resetKeranjang() {
        cart = [];
        clearInputBarang();
        document.getElementById('barangSelect').value = '';
        document.getElementById('jumlahBayar').value = '';
        document.getElementById('kembalian').value = '0';
        updateCartDisplay();
        document.getElementById('kodeBarang').focus();
    }

    /**
     * Clear Input Barang
     */
    function clearInputBarang() {
        document.getElementById('kodeBarang').value = '';
        document.getElementById('namaBarang').value = '';
        document.getElementById('hargaBarang').value = '';
        document.getElementById('jumlahBarang').value = '1';
        document.getElementById('barangSelect').value = '';
    }

    /**
     * Clear Totals
     */
    function clearTotals() {
        document.getElementById('totalJumlah').textContent = '0';
        document.getElementById('totalHarga').textContent = '0';
    }

    /**
     * Format Rupiah
     */
    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }

    /**
     * Show Alert
     */
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
</script>
@endsection
