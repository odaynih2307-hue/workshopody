<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Cetak Label PDF (5 x 8 = 40)
     */
    public function cetak(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'x' => 'required|numeric|min:1|max:5',
            'y' => 'required|numeric|min:1|max:8'
        ]);

        $x = $request->x;
        $y = $request->y;

        $barang = Barang::whereIn('id', $request->selected)->get();

        // Hitung posisi awal
        $startIndex = (($y - 1) * 5) + ($x - 1);

        // Siapkan 40 slot label kosong
        $labels = array_fill(0, 40, null);

        foreach ($barang as $index => $item) {
            if (($startIndex + $index) < 40) {
                $labels[$startIndex + $index] = $item;
            }
        }

        $pdf = Pdf::loadView('barang.cetak', compact('labels'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('label_tnj_108.pdf');
    }

    /**
     * Show Form HTML Table
     */
    public function formHtml()
    {
        return view('barang.form-html');
    }

    /**
     * Show Form DataTables
     */
    public function formDatatable()
    {
        return view('barang.form-datatable');
    }

    /**
     * AJAX API: Get all barang as JSON
     */
    public function getAllBarang()
    {
        try {
            $barang = Barang::select('id', 'id_barang', 'nama_barang', 'harga')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diambil',
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
     * AJAX API: Store barang
     */
    public function storeBarangAjax(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0'
            ]);

            $barang = Barang::create([
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $barang
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
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
     * AJAX API: Update barang
     */
    public function updateBarangAjax(Request $request, string $id)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0'
            ]);

            $barang = Barang::findOrFail($id);
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $barang
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX API: Delete barang
     */
    public function destroyBarangAjax(string $id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}