<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kota = Kota::all();
        return view('kota.index', compact('kota'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kota.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kota' => 'required',
            'provinsi' => 'required'
        ]);

        Kota::create([
            'nama_kota' => $request->nama_kota,
            'provinsi' => $request->provinsi
        ]);

        return redirect()->route('kota.index')
                         ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kota $kota)
    {
        return view('kota.show', compact('kota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kota $kota)
    {
        return view('kota.edit', compact('kota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kota $kota)
    {
        $request->validate([
            'nama_kota' => 'required',
            'provinsi' => 'required'
        ]);

        $kota->update([
            'nama_kota' => $request->nama_kota,
            'provinsi' => $request->provinsi
        ]);

        return redirect()->route('kota.index')
                         ->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kota $kota)
    {
        $kota->delete();

        return redirect()->route('kota.index')
                         ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Show Form HTML Table
     */
    public function formHtml()
    {
        return view('kota.form-html');
    }

    /**
     * Show Form DataTables
     */
    public function formDatatable()
    {
        return view('kota.form-datatable');
    }

    /**
     * AJAX API: Get all kota as JSON
     */
    public function getAllKota()
    {
        try {
            $kota = Kota::select('id', 'nama_kota', 'provinsi')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => $kota
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX API: Store kota
     */
    public function storeKotaAjax(Request $request)
    {
        try {
            $request->validate([
                'nama_kota' => 'required|string|max:255',
                'provinsi' => 'required|string|max:255'
            ]);

            $kota = Kota::create([
                'nama_kota' => $request->nama_kota,
                'provinsi' => $request->provinsi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $kota
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
     * AJAX API: Update kota
     */
    public function updateKotaAjax(Request $request, string $id)
    {
        try {
            $request->validate([
                'nama_kota' => 'required|string|max:255',
                'provinsi' => 'required|string|max:255'
            ]);

            $kota = Kota::findOrFail($id);
            $kota->update([
                'nama_kota' => $request->nama_kota,
                'provinsi' => $request->provinsi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $kota
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
     * AJAX API: Delete kota
     */
    public function destroyKotaAjax(string $id)
    {
        try {
            $kota = Kota::findOrFail($id);
            $kota->delete();

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
