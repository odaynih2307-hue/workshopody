@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="mdi mdi-storefront text-primary"></i> Pesan Kantin Online</h2>
            <p class="text-muted">Pilih vendor, tambahkan menu ke keranjang, dan bayar lewat QRIS / Virtual Account.</p>
        </div>
    </div>

    <div class="row">
        <!-- Input Pemesanan -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 fw-bold">Pilih Menu</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Vendor Kantin</label>
                        <select class="form-select form-select-lg" id="vendorSelect" onchange="loadMenus()">
                            <option value="">-- Silahkan Pilih Kantin --</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Menu Makanan/Minuman</label>
                        <select class="form-select form-select-lg" id="menuSelect" disabled onchange="selectMenu()">
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="text" class="form-control bg-light" id="hargaMenuShow" readonly>
                            <input type="hidden" id="hargaMenu">
                            <input type="hidden" id="namaMenu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number" class="form-control" id="jumlahMenu" value="1" min="1" disabled>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-lg rounded-3" id="tambahBtn" disabled onclick="tambahKeKeranjang()">
                            <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keranjang & Checkout -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 fw-bold">Keranjang Pesanan</h5>
                </div>
                <div class="card-body">
                    <div id="emptyCart" class="text-center py-5 text-muted">
                        <i class="mdi mdi-cart-outline" style="font-size: 4rem;"></i>
                        <p class="mt-2">Keranjang Anda masih kosong</p>
                    </div>

                    <div class="table-responsive" id="cartContent" style="display: none;">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th width="15%">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody"></tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded-3">
                            <h5 class="mb-0 text-muted">Total Bayar:</h5>
                            <h3 class="mb-0 fw-bold text-primary">Rp <span id="totalBayar">0</span></h3>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-success btn-lg rounded-3 fw-bold" id="checkoutBtn" onclick="prosesCheckout()">
                                <i class="mdi mdi-credit-card"></i> Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MIDTRANS JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let currentMenus = [];
    let cart = [];
    let totalBayar = 0;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Cascading Load Menus
    function loadMenus() {
        const idvendor = document.getElementById('vendorSelect').value;
        const menuSelect = document.getElementById('menuSelect');
        
        menuSelect.innerHTML = '<option value="">-- Loading... --</option>';
        menuSelect.disabled = true;
        document.getElementById('tambahBtn').disabled = true;
        document.getElementById('jumlahMenu').disabled = true;

        if(!idvendor) {
            menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
            return;
        }

        axios.get(`/kantin/api/menus/${idvendor}`)
            .then(res => {
                currentMenus = res.data.data;
                menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
                currentMenus.forEach(m => {
                    menuSelect.innerHTML += `<option value="${m.idmenu}">${m.nama_menu}</option>`;
                });
                menuSelect.disabled = false;
            })
            .catch(err => {
                console.error(err);
                alert("Gagal memuat menu kantin.");
            });
    }

    function selectMenu() {
        const idmenu = document.getElementById('menuSelect').value;
        if(!idmenu) {
            document.getElementById('tambahBtn').disabled = true;
            document.getElementById('jumlahMenu').disabled = true;
            document.getElementById('hargaMenuShow').value = '';
            return;
        }

        const menu = currentMenus.find(m => m.idmenu == idmenu);
        if(menu) {
            document.getElementById('hargaMenuShow').value = 'Rp ' + formatRupiah(menu.harga);
            document.getElementById('hargaMenu').value = menu.harga;
            document.getElementById('namaMenu').value = menu.nama_menu;
            document.getElementById('jumlahMenu').disabled = false;
            document.getElementById('tambahBtn').disabled = false;
        }
    }

    function tambahKeKeranjang() {
        const idmenu = document.getElementById('menuSelect').value;
        const nama = document.getElementById('namaMenu').value;
        const harga = parseInt(document.getElementById('hargaMenu').value);
        const jumlah = parseInt(document.getElementById('jumlahMenu').value);

        if(!idmenu || jumlah < 1) return;

        const existing = cart.find(c => c.idmenu == idmenu);
        if(existing) {
            existing.jumlah += jumlah;
            existing.subtotal = existing.jumlah * existing.harga;
        } else {
            cart.push({
                idmenu: idmenu,
                nama_menu: nama,
                harga: harga,
                jumlah: jumlah,
                subtotal: harga * jumlah
            });
        }

        renderCart();
        
        // reset input form
        document.getElementById('menuSelect').value = '';
        document.getElementById('jumlahMenu').value = '1';
        selectMenu();
    }

    function hapusItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const emptyCart = document.getElementById('emptyCart');
        const cartContent = document.getElementById('cartContent');
        
        tbody.innerHTML = '';
        totalBayar = 0;

        if(cart.length === 0) {
            emptyCart.style.display = 'block';
            cartContent.style.display = 'none';
            return;
        }

        emptyCart.style.display = 'none';
        cartContent.style.display = 'block';

        cart.forEach((item, index) => {
            totalBayar += item.subtotal;
            tbody.innerHTML += `
                <tr>
                    <td class="fw-semibold text-primary">${item.nama_menu}</td>
                    <td>Rp ${formatRupiah(item.harga)}</td>
                    <td>${item.jumlah}</td>
                    <td class="text-end fw-bold">Rp ${formatRupiah(item.subtotal)}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger shadow-sm" onclick="hapusItem(${index})"><i class="mdi mdi-delete"></i></button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('totalBayar').innerText = formatRupiah(totalBayar);
    }

    function prosesCheckout() {
        if(cart.length === 0) return;

        const btn = document.getElementById('checkoutBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';

        axios.post('{{ route("kantin.checkout") }}', {
            items: cart,
            total_harga: totalBayar
        })
        .then(response => {
            if(response.data.status && response.data.snap_token) {
                // Panggil Midtrans Snap
                snap.pay(response.data.snap_token, {
                    onSuccess: function(result){
                        // Manual Sync karena Webhook tidak mencapai localhost
                        axios.post('/kantin/api/sync-payment/' + response.data.order_id)
                            .then(() => {
                                Swal.fire('Sukses!', 'Pembayaran berhasil dikonfirmasi.', 'success').then(() => {
                                    window.location.reload();
                                });
                            });
                    },
                    onPending: function(result){
                        Swal.fire('Menunggu...', 'Silahkan selesaikan pembayaran Anda.', 'info').then(() => {
                            cart = []; renderCart(); btn.disabled = false; btn.innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
                        });
                    },
                    onError: function(result){
                        Swal.fire('Gagal!', 'Pembayaran gagal.', 'error');
                        btn.disabled = false; btn.innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
                    },
                    onClose: function(){
                        Swal.fire('Batal', 'Anda menutup pop-up sebelum menyelesaikan pembayaran.', 'warning');
                        btn.disabled = false; btn.innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
                    }
                });
            } else {
                Swal.fire('Error', 'Gagal mendapatkan token pembayaran', 'error');
                btn.disabled = false; btn.innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            btn.disabled = false; btn.innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
        });
    }
</script>
@endsection
