@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🍽️ Master Menu Kantin</h3>
    <a href="{{ route('kantin.menu.create') }}" class="btn btn-primary shadow-sm"><i class="mdi mdi-plus"></i> Tambah Menu</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Vendor</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th width="18%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->vendor ? $item->vendor->nama_vendor : '-' }}</td>
                        <td class="fw-semibold text-primary">{{ $item->nama_menu }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($item->path_gambar)
                            <img src="{{ Storage::url($item->path_gambar) }}" alt="Menu" class="img-thumbnail" width="50" height="50">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('kantin.menu.edit', $item->idmenu) }}" class="btn btn-warning btn-sm me-1"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{ route('kantin.menu.destroy', $item->idmenu) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Belum ada data menu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
