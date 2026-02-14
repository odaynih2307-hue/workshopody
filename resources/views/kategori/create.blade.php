@extends('layouts.app')

@section('content')

<h2>Tambah Kategori</h2>

<form action="{{ route('kategori.store') }}" method="POST">
    @csrf

    <label>Nama Kategori</label><br>
    <input type="text" name="nama_kategori"><br><br>

    <button type="submit">Simpan</button>
</form>

@endsection