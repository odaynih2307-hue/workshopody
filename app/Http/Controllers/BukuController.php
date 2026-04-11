<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'kode' => 'required|unique:buku,kode',
            'judul' => 'required',
            'pengarang' => 'required',
        ]);

        Buku::create([
            'idkategori' => $request->kategori_id,
            'kode' => $request->kode,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
        ]);

        return redirect()->route('buku.index')
            ->with('success','Buku berhasil ditambahkan');
    }
}
