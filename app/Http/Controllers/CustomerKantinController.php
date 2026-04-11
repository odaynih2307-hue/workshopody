<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerKantinController extends Controller
{
    public function index()
    {
        $vendors = \App\Models\VendorKantin::all();
        return view('kantin.customer.pos', compact('vendors'));
    }

    public function getMenus($idvendor)
    {
        $menus = \App\Models\MenuKantin::where('idvendor', $idvendor)->get();
        return response()->json([
            'status' => true,
            'data' => $menus
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total_harga' => 'required|numeric|min:1'
        ]);

        $guestName = 'Guest_' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Buat pesanan utama
        $pesanan = \App\Models\Pesanan::create([
            'nama' => $guestName,
            'total' => $request->total_harga,
            'status_bayar' => 'Pending'
        ]);

        // Detail pesanan list untuk Midtrans
        $item_details = [];
        
        foreach ($request->items as $item) {
            \App\Models\DetailPesanan::create([
                'idpesanan' => $pesanan->idpesanan,
                'idmenu' => $item['idmenu'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal']
            ]);

            $item_details[] = [
                'id' => $item['idmenu'],
                'price' => $item['harga'],
                'quantity' => $item['jumlah'],
                'name' => mb_substr($item['nama_menu'], 0, 50)
            ];
        }

        // Set konfigurasi midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $pesanan->idpesanan,
                'gross_amount' => $request->total_harga,
            ),
            'customer_details' => array(
                'first_name' => $guestName,
            ),
            'item_details' => $item_details
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Simpan snap token
            $pesanan->snap_token = $snapToken;
            $pesanan->save();

            return response()->json([
                'status' => true,
                'snap_token' => $snapToken,
                'order_id' => $pesanan->idpesanan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function syncPayment($order_id)
    {
        $pesanan = \App\Models\Pesanan::find($order_id);
        if ($pesanan && $pesanan->status_bayar === 'Pending') {
            // Karena localhost tidak dijangkau webhook midtrans, 
            // fungsi ini dijalankan via onSuccess frontend sebagai bypass local.
            $pesanan->status_bayar = 'Lunas';
            $pesanan->save();
        }
        return response()->json(['status' => true]);
    }
}
