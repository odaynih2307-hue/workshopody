@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Tambah Menu</h3>
    <a href="{{ route('kantin.menu.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('kantin.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Vendor</label>
                <select name="idvendor" class="form-select" required>
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $v)
                    <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" required min="0">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Upload Gambar (Optional)</label>
                <input type="file" name="path_gambar" class="form-control" accept="image/*">
            </div>
            <div class="text-end">
                <button class="btn btn-primary px-4"><i class="mdi mdi-content-save"></i> Simpan Menu</button>
            </div>
        </form>
    </div>
</div>
@endsection
