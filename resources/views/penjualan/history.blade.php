@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>History Penjualan</h2>
            <p class="text-muted">Data riwayat transaksi penjualan</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('kasir.index') }}" class="btn btn-success">Kembali ke Kasir</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Penjualan</h5>
                </div>
                <div class="card-body">
                    <div id="historyContainer" style="overflow-x: auto;">
                        <div id="emptyHistory" class="alert alert-info text-center">
                            <p>Memuat data...</p>
                        </div>
                        <table id="historyTable" class="table table-hover table-striped" style="display: none;">
                            <thead class="table-dark">
                                <tr>
                                    <th>No. Penjualan</th>
                                    <th>Tanggal</th>
                                    <th>Kasir</th>
                                    <th style="text-align: center;">Jumlah Barang</th>
                                    <th class="text-end">Total Harga</th>
                                    <th class="text-end">Bayar</th>
                                    <th class="text-end">Kembalian</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historyBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>No. Penjualan:</strong> <span id="detailNoPenjualan">-</span></p>
                        <p><strong>Tanggal:</strong> <span id="detailTanggal">-</span></p>
                        <p><strong>Kasir:</strong> <span id="detailKasir">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Barang:</strong> <span id="detailJumlah">-</span></p>
                        <p><strong>Status:</strong> <span id="detailStatus">-</span></p>
                    </div>
                </div>

                <hr>

                <h6>Detail Barang:</h6>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detailItems">
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Total Harga:</strong></td>
                                <td class="text-end"><strong>Rp <span id="detailTotalHarga">0</span></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Bayar:</strong></td>
                                <td class="text-end"><strong>Rp <span id="detailJumlahBayar">0</span></strong></td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Kembalian:</strong></td>
                                <td class="text-end"><strong>Rp <span id="detailKembalian">0</span></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printDetail()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let historyData = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadHistory();
    });

    /**
     * Load history penjualan
     */
    function loadHistory() {
        axios.get('{{ route("penjualan.api.history") }}')
            .then(response => {
                if (response.data.status) {
                    historyData = response.data.data;
                    displayHistory();
                } else {
                    showAlert('Gagal memuat data penjualan', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
            });
    }

    /**
     * Display history di tabel
     */
    function displayHistory() {
        const emptyHistory = document.getElementById('emptyHistory');
        const historyTable = document.getElementById('historyTable');
        const historyBody = document.getElementById('historyBody');

        if (historyData.length === 0) {
            emptyHistory.innerHTML = '<p class="text-center text-muted">Tidak ada data penjualan</p>';
            emptyHistory.style.display = 'block';
            historyTable.style.display = 'none';
            return;
        }

        emptyHistory.style.display = 'none';
        historyTable.style.display = 'table';
        historyBody.innerHTML = '';

        historyData.forEach((item, index) => {
            const tanggal = new Date(item.tanggal_penjualan).toLocaleString('id-ID');
            const statusBadge = item.status === 'selesai' 
                ? '<span class="badge bg-success">Selesai</span>' 
                : '<span class="badge bg-danger">Batal</span>';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${item.no_penjualan}</strong></td>
                <td><small>${tanggal}</small></td>
                <td>${item.user.name}</td>
                <td style="text-align: center;">${item.total_jumlah}</td>
                <td class="text-end">Rp ${formatRupiah(item.total_harga)}</td>
                <td class="text-end">Rp ${formatRupiah(item.jumlah_bayar)}</td>
                <td class="text-end">Rp ${formatRupiah(item.kembalian)}</td>
                <td style="text-align: center;">${statusBadge}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-info" onclick="showDetail(${index})">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                </td>
            `;
            historyBody.appendChild(row);
        });
    }

    /**
     * Show detail penjualan
     */
    function showDetail(index) {
        const penjualan = historyData[index];
        const tanggal = new Date(penjualan.tanggal_penjualan).toLocaleString('id-ID');
        const statusBadge = penjualan.status === 'selesai' 
            ? '<span class="badge bg-success">Selesai</span>' 
            : '<span class="badge bg-danger">Batal</span>';

        document.getElementById('detailNoPenjualan').textContent = penjualan.no_penjualan;
        document.getElementById('detailTanggal').textContent = tanggal;
        document.getElementById('detailKasir').textContent = penjualan.user.name;
        document.getElementById('detailJumlah').textContent = penjualan.total_jumlah;
        document.getElementById('detailStatus').innerHTML = statusBadge;
        document.getElementById('detailTotalHarga').textContent = formatRupiah(penjualan.total_harga);
        document.getElementById('detailJumlahBayar').textContent = formatRupiah(penjualan.jumlah_bayar);
        document.getElementById('detailKembalian').textContent = formatRupiah(penjualan.kembalian);

        // Display items
        const detailItems = document.getElementById('detailItems');
        detailItems.innerHTML = '';

        penjualan.penjualan_details.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.barang.id_barang}</td>
                <td>${item.barang.nama_barang}</td>
                <td style="text-align: center;">${item.jumlah}</td>
                <td class="text-end">Rp ${formatRupiah(item.harga)}</td>
                <td class="text-end">Rp ${formatRupiah(item.subtotal)}</td>
            `;
            detailItems.appendChild(row);
        });

        // Show modal
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        detailModal.show();

        // Store current detail for printing
        window.currentDetail = penjualan;
    }

    /**
     * Print detail
     */
    function printDetail() {
        if (!window.currentDetail) return;

        const penjualan = window.currentDetail;
        const tanggal = new Date(penjualan.tanggal_penjualan).toLocaleString('id-ID');

        let printContent = `
<!DOCTYPE html>
<html>
<head>
    <title>Invoice ${penjualan.no_penjualan}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; width: 50%; margin-left: auto; }
        .summary table { border: none; }
        .summary td { border: none; padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE PENJUALAN</h2>
        <p>No. Penjualan: ${penjualan.no_penjualan}</p>
    </div>

    <p><strong>Tanggal:</strong> ${tanggal}</p>
    <p><strong>Kasir:</strong> ${penjualan.user.name}</p>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
`;

        penjualan.penjualan_details.forEach(item => {
            printContent += `
            <tr>
                <td>${item.barang.id_barang}</td>
                <td>${item.barang.nama_barang}</td>
                <td class="text-right">${item.jumlah}</td>
                <td class="text-right">Rp ${formatRupiah(item.harga)}</td>
                <td class="text-right">Rp ${formatRupiah(item.subtotal)}</td>
            </tr>
`;
        });

        printContent += `
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Barang:</strong></td>
                <td class="text-right"><strong>${penjualan.total_jumlah}</strong></td>
            </tr>
            <tr>
                <td><strong>Total Harga:</strong></td>
                <td class="text-right"><strong>Rp ${formatRupiah(penjualan.total_harga)}</strong></td>
            </tr>
            <tr>
                <td><strong>Jumlah Bayar:</strong></td>
                <td class="text-right"><strong>Rp ${formatRupiah(penjualan.jumlah_bayar)}</strong></td>
            </tr>
            <tr style="background-color: #90EE90;">
                <td><strong>Kembalian:</strong></td>
                <td class="text-right"><strong>Rp ${formatRupiah(penjualan.kembalian)}</strong></td>
            </tr>
        </table>
    </div>

    <p style="margin-top: 30px; text-align: center; color: #666;">
        Terima kasih atas pembelian Anda!
    </p>
</body>
</html>
        `;

        const printWindow = window.open('', '', 'height=600,width=900');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
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
