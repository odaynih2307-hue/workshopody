@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">📚 Data Buku</h3>

    <a href="{{ route('buku.create') }}" class="btn btn-warning shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah Buku
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th width="18%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($buku as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold text-primary">
                            {{ $item->kode }}
                        </td>

                        <td>{{ $item->judul }}</td>

                        <td>{{ $item->pengarang }}</td>

                        {{-- KATEGORI (ANTI ERROR NULL) --}}
                        <td>
                            @if($item->kategori)
                                <span class="badge bg-info text-dark px-3 py-2">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">
                                    Tidak ada kategori
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">
                            <a href="{{ route('buku.edit', $item->idbuku) }}"
                               class="btn btn-warning btn-sm me-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('buku.destroy', $item->idbuku) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus buku ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-journal-x fs-1"></i>
                            <p class="mt-2 mb-0">Belum ada data buku</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
