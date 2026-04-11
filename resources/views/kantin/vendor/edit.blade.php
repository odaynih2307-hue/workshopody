@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Edit Vendor</h3>
    <a href="{{ route('kantin.vendor.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('kantin.vendor.update', $vendor->idvendor) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Vendor</label>
                <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" class="form-control" required>
            </div>
            <div class="text-end">
                <button class="btn btn-warning px-4"><i class="mdi mdi-content-save"></i> Update Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection
