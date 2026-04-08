@extends('layouts.app')

@section('title', 'Form Barang - HTML Table')

@section('style-page')
<style>
    #tbody-barang-html tr { cursor: pointer; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-package-variant"></i>
        </span> Form Barang (HTML Table)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Form Barang - HTML Table</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Barang</h4>

                <form id="form-barang-html" class="forms-sample">
                    <div class="form-group">
                        <label for="nama_barang_html">Nama barang :</label>
                        <input type="text" class="form-control" id="nama_barang_html"
                               placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_barang_html">Harga barang:</label>
                        <input type="number" class="form-control" id="harga_barang_html"
                               placeholder="Masukkan harga barang" min="0" required>
                    </div>
                </form>
                <div class="d-flex justify-content-end mt-3 mb-4">
                    <button type="button" id="btn-submit-html" class="btn btn-gradient-success">
                        <span id="btn-submit-html-text"><i class="mdi mdi-check"></i> submit</span>
                        <span id="btn-submit-html-spinner" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Memproses...
                        </span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabel-barang-html">
                        <thead>
                            <tr>
                                <th>ID barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-barang-html">
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit/Hapus Barang -->
<div class="modal fade" id="modal-barang-html" tabindex="-1" role="dialog" aria-labelledby="modalBarangHtmlLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangHtmlLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-modal-html">
                    <div class="form-group">
                        <label for="modal-id-html">ID barang :</label>
                        <input type="text" class="form-control" id="modal-id-html" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal-nama-html">Nama barang :</label>
                        <input type="text" class="form-control" id="modal-nama-html" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-harga-html">Harga barang:</label>
                        <input type="number" class="form-control" id="modal-harga-html" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" id="btn-hapus-html" class="btn btn-gradient-danger">
                    <span id="btn-hapus-html-text"><i class="mdi mdi-delete"></i> Hapus</span>
                    <span id="btn-hapus-html-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menghapus...
                    </span>
                </button>
                <button type="button" id="btn-simpan-html" class="btn btn-gradient-success">
                    <span id="btn-simpan-html-text"><i class="mdi mdi-pencil"></i> Simpan</span>
                    <span id="btn-simpan-html-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script src="{{ asset('/assets/js/form-html.js') }}"></script>
@endsection