@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-package-variant"></i>
        </span> Data Barang
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Barang</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Daftar Barang</h4>
                    <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary">
                        <i class="mdi mdi-plus"></i> Tambah Barang
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </div>
                                </th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barang as $b)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input barang-checkbox" 
                                               form="formCetak"
                                               name="selected[]" 
                                               value="{{ $b->id }}">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $b->id_barang }}</span>
                                </td>
                                <td><strong>{{ $b->nama_barang }}</strong></td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        Rp {{ number_format($b->harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('barang.edit', $b->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="{{ route('barang.destroy', $b->id) }}" 
                                              method="POST" 
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin hapus?')"
                                                    title="Hapus">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="mdi mdi-inbox"></i> Belum ada data barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr class="my-4">

                {{-- SECTION CETAK LABEL --}}
                <div class="mt-4 p-3 bg-light rounded">
                    <h5 class="mb-3">
                        <i class="mdi mdi-printer"></i> Cetak Label Barang
                    </h5>

                    <form action="{{ route('barang.cetak') }}" 
                          method="POST" 
                          id="formCetak">
                        @csrf

                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <label class="form-label">Posisi X (1-5)</label>
                                <input type="number" 
                                       name="x" 
                                       class="form-control"
                                       min="1" 
                                       max="5" 
                                       placeholder="1"
                                       required>
                                <small class="text-muted">Kolom (horizontal)</small>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Posisi Y (1-8)</label>
                                <input type="number" 
                                       name="y" 
                                       class="form-control"
                                       min="1" 
                                       max="8" 
                                       placeholder="1"
                                       required>
                                <small class="text-muted">Baris (vertikal)</small>
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-gradient-success w-100">
                                    <i class="mdi mdi-printer"></i> Cetak Label
                                </button>
                            </div>

                            <div class="col-md-5 text-muted">
                                <small>
                                    <i class="mdi mdi-information"></i>
                                    Pilih barang dengan checkbox, atur posisi awal, lalu cetak label PDF.
                                    Format kertas: A4 (5×8 = 40 label per halaman)
                                </small>
                            </div>
                        </div>

                        @if($errors->has('selected'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <i class="mdi mdi-alert"></i> {{ $errors->first('selected') }}
                            </div>
                        @endif

                        @if($errors->has('x') || $errors->has('y'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <i class="mdi mdi-alert"></i> 
                                Silakan masukkan posisi X dan Y yang valid
                            </div>
                        @endif

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Select All / Deselect All
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.barang-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Form validation sebelum cetak
    document.getElementById('formCetak').addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.barang-checkbox:checked');
        if (selected.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 barang untuk dicetak!');
            return false;
        }
    });
</script>

@endsection