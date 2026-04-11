@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Tambah Vendor Baru</h3>
    <a href="{{ route('kantin.vendor.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('kantin.vendor.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Vendor</label>
                <input type="text" name="nama_vendor" value="{{ old('nama_vendor') }}" class="form-control" required placeholder="Contoh: Kedai Makmur">
            </div>
            <div class="text-end">
                <button class="btn btn-primary px-4"><i class="mdi mdi-content-save"></i> Simpan Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection
