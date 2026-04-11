@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Tambah Buku</h3>

    <a href="{{ route('buku.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <form action="{{ route('buku.store') }}" method="POST">
            @csrf

            {{-- KATEGORI --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">-- Pilih Kategori --</option>

                    @foreach($kategori as $item)
                        <option value="{{ $item->idkategori }}" {{ old('kategori_id') == $item->idkategori ? 'selected' : '' }}>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                @error('kategori_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KODE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Buku</label>
                <input type="text"
                       name="kode"
                       value="{{ old('kode') }}"
                       class="form-control @error('kode') is-invalid @enderror"
                       placeholder="Contoh: NV-01">

                @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- JUDUL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Buku</label>
                <input type="text"
                       name="judul"
                       value="{{ old('judul') }}"
                       class="form-control @error('judul') is-invalid @enderror"
                       placeholder="Masukkan judul buku">

                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- PENGARANG --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Pengarang</label>
                <input type="text"
                       name="pengarang"
                       value="{{ old('pengarang') }}"
                       class="form-control @error('pengarang') is-invalid @enderror"
                       placeholder="Nama pengarang">

                @error('pengarang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <button class="btn btn-warning px-4">
                    <i class="bi bi-save"></i> Simpan Buku
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
