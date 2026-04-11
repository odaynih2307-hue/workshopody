@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🛒 Histori Transaksi</h3>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Waktu Pesan</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                        <th>Total Terbayar</th>
                        <th>Detail Item</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold text-primary">#ORD-{{ str_pad($p->idpesanan, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->timestamp)->format('d M Y, H:i') }}</td>
                        <td>
                            @if(strtolower($p->status_bayar) == 'lunas')
                                <span class="badge bg-success px-3 py-2"><i class="mdi mdi-check-circle"></i> LUNAS</span>
                            @elseif(strtolower($p->status_bayar) == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2"><i class="mdi mdi-clock-outline"></i> PENDING</span>
                            @else
                                <span class="badge bg-danger px-3 py-2"><i class="mdi mdi-close-circle"></i> {{ strtoupper($p->status_bayar) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info px-3 py-2 text-white">{{ strtoupper($p->metode_bayar ?? 'MIDTRANS') }}</span>
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach($p->detail as $dtl)
                                <li>{{ $dtl->jumlah }}x {{ $dtl->menu->nama_menu ?? 'Menu Dihapus' }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">Belum ada transaksi pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
