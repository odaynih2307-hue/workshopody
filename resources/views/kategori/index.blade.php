@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Kategori</h2>

    <a href="{{ route('kategori.create') }}" class="btn btn-primary" style="margin-bottom: 15px; display: inline-block;">Tambah Kategori</a>

    @if(session('success'))
        <p style="color:green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $k)
            <tr>
                {{-- Pastikan nama properti sesuai database: idkategori --}}
                <td>{{ $k->idkategori }}</td> 
                <td>{{ $k->nama_kategori }}</td>
                <td>
                    <form action="{{ route('kategori.destroy', $k->idkategori) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        
                        <a href="{{ route('kategori.edit', $k->idkategori) }}" class="btn btn-sm btn-warning">Edit</a> | 
                        
                        <button type="submit" style="color:red; cursor:pointer; background:none; border:none; padding:0; text-decoration:underline;">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection