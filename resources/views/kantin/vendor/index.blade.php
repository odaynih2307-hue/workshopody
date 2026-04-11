@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">🏢 Master Vendor Kantin</h3>
    <a href="{{ route('kantin.vendor.create') }}" class="btn btn-primary shadow-sm">
        <i class="mdi mdi-plus"></i> Tambah Vendor
    </a>
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
                        <th>ID Vendor</th>
                        <th>Nama Vendor</th>
                        <th width="18%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>VD-{{ str_pad($item->idvendor, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="fw-semibold text-primary">{{ $item->nama_vendor }}</td>
                        <td class="text-center">
                            <a href="{{ route('kantin.vendor.edit', $item->idvendor) }}" class="btn btn-warning btn-sm me-1"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{ route('kantin.vendor.destroy', $item->idvendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus vendor ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">Belum ada data vendor</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
