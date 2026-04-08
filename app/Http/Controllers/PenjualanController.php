<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Barang;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Show POS/Kasir page
     */
    public function index()
    {
        $barang = Barang::select('id', 'id_barang', 'nama_barang', 'harga')->get();
        return view('penjualan.kasir', compact('barang'));
    }

    /**
     * Search Barang by kode
     */
    public function searchBarang($kode)
    {
        try {
            $barang = Barang::where('id_barang', $kode)->first();
            
            if (!$barang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data Barang ditemukan',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all Barang for dropdown
     */
    public function getAllBarang()
    {
        try {
            $barang = Barang::select('id', 'id_barang', 'nama_barang', 'harga')->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Data Barang berhasil diambil',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Penjualan
     */
    public function savePenjualan(Request $request)
    {
        try {
            $request->validate([
                'total_jumlah' => 'required|numeric|min:1',
                'total_harga' => 'required|numeric|min:1',
                'jumlah_bayar' => 'required|numeric|min:1',
                'items' => 'required|array|min:1'
            ]);

            // Generate no_penjualan
            $lastPenjualan = Penjualan::orderBy('id', 'desc')->first();
            $noPenjualan = 'PJ' . date('Ymd') . str_pad(($lastPenjualan ? $lastPenjualan->id + 1 : 1), 5, '0', STR_PAD_LEFT);

            // Hitung kembalian
            $kembalian = $request->jumlah_bayar - $request->total_harga;

            // Simpan Penjualan
            $penjualan = Penjualan::create([
                'no_penjualan' => $noPenjualan,
                'user_id' => auth()->id(),
                'tanggal_penjualan' => now(),
                'total_jumlah' => $request->total_jumlah,
                'total_harga' => $request->total_harga,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $kembalian,
                'status' => 'selesai'
            ]);

            // Simpan Detail Penjualan
            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Penjualan berhasil disimpan',
                'data' => [
                    'no_penjualan' => $noPenjualan,
                    'kembalian' => $kembalian
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Penjualan history
     */
    public function history()
    {
        try {
            $penjualan = Penjualan::with('penjualanDetails.barang')
                                  ->orderBy('id', 'desc')
                                  ->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Data Penjualan berhasil diambil',
                'data' => $penjualan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
