<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuKantinController extends Controller
{
    public function index()
    {
        $menus = \App\Models\MenuKantin::with('vendor')->get();
        return view('kantin.menu.index', compact('menus'));
    }

    public function create()
    {
        $vendors = \App\Models\VendorKantin::all();
        return view('kantin.menu.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $data = $request->except('path_gambar');
        if($request->hasFile('path_gambar')) {
            $data['path_gambar'] = $request->file('path_gambar')->store('menu_kantin', 'public');
        }

        \App\Models\MenuKantin::create($data);
        return redirect()->route('kantin.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menu = \App\Models\MenuKantin::findOrFail($id);
        $vendors = \App\Models\VendorKantin::all();
        return view('kantin.menu.edit', compact('menu', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $menu = \App\Models\MenuKantin::findOrFail($id);
        $data = $request->except('path_gambar');
        if($request->hasFile('path_gambar')) {
            $data['path_gambar'] = $request->file('path_gambar')->store('menu_kantin', 'public');
        }

        $menu->update($data);
        return redirect()->route('kantin.menu.index')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($id)
    {
        \App\Models\MenuKantin::destroy($id);
        return redirect()->route('kantin.menu.index')->with('success', 'Menu berhasil dihapus');
    }
}
