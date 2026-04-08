<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    public function cetak(Request $request)
{
    // 🔥 paksa jadi array (ANTI ERROR)
    $ids = $request->input('barang_ids', []);

    if (!is_array($ids)) {
        $ids = [$ids];
    }

    // 🔥 validasi ringan
    if (empty($ids)) {
        return back()->with('error', 'Pilih minimal 1 barang!');
    }

    $x = (int) $request->input('x', 1);
    $y = (int) $request->input('y', 1);

    // ✅ ambil data
    $data = Barang::whereIn('id', $ids)->get();

    // 🔴 DEBUG SUPER PENTING (sementara aktifkan)
    // dd([
    //     'ids' => $ids,
    //     'jumlah_data' => $data->count()
    // ]);

    // hitung posisi awal
    $startIndex = (($y - 1) * 5) + ($x - 1);

    // siapkan 40 slot
    $labels = array_fill(0, 40, null);

    foreach ($data as $i => $item) {
        $pos = $startIndex + $i;
        if ($pos < 40) {
            $labels[$pos] = $item;
        }
    }

    $pdf = Pdf::loadView('pdf.label', [
        'labels' => $labels
    ])->setPaper('A4');

    return $pdf->stream('label.pdf');
}
}