<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorKantinController extends Controller
{
    public function index()
    {
        $vendors = \App\Models\VendorKantin::all();
        return view('kantin.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('kantin.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_vendor' => 'required|string|max:255']);
        \App\Models\VendorKantin::create($request->all());
        return redirect()->route('kantin.vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit($id)
    {
        $vendor = \App\Models\VendorKantin::findOrFail($id);
        return view('kantin.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_vendor' => 'required|string|max:255']);
        $vendor = \App\Models\VendorKantin::findOrFail($id);
        $vendor->update($request->all());
        return redirect()->route('kantin.vendor.index')->with('success', 'Vendor berhasil diupdate');
    }

    public function destroy($id)
    {
        \App\Models\VendorKantin::destroy($id);
        return redirect()->route('kantin.vendor.index')->with('success', 'Vendor berhasil dihapus');
    }
}
