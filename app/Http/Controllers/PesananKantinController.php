<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PesananKantinController extends Controller
{
    public function index()
    {
        $pesanans = \App\Models\Pesanan::with('detail.menu')
                        ->orderBy('timestamp', 'desc')
                        ->get();
        return view('kantin.pesanan.index', compact('pesanans'));
    }
}
