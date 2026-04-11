@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Edit Menu</h3>
    <a href="{{ route('kantin.menu.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('kantin.menu.update', $menu->idmenu) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Vendor</label>
                <select name="idvendor" class="form-select" required>
                    @foreach($vendors as $v)
                    <option value="{{ $v->idvendor }}" {{ $menu->idvendor == $v->idvendor ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Menu</label>
                <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ $menu->harga }}" class="form-control" required min="0">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Upload Gambar Baru (Opsional)</label>
                <input type="file" name="path_gambar" class="form-control" accept="image/*">
                @if($menu->path_gambar)
                <div class="mt-2 text-muted text-sm">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</div>
                @endif
            </div>
            <div class="text-end">
                <button class="btn btn-warning px-4"><i class="mdi mdi-content-save"></i> Update Menu</button>
            </div>
        </form>
    </div>
</div>
@endsection
