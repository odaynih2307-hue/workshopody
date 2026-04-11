<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Set konfigurasi midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        
        try {
            $notification = new \Midtrans\Notification();
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id; // Biasanya diisi dengan Pesanan ID
            
            $pesanan = Pesanan::find($orderId);
            if(!$pesanan) {
                return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
            }

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $pesanan->status_bayar = 'Lunas';
                $pesanan->metode_bayar = $notification->payment_type ?? $pesanan->metode_bayar;
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $pesanan->status_bayar = 'Batal';
            } else if ($transactionStatus == 'pending') {
                $pesanan->status_bayar = 'Pending';
            }

            $pesanan->save();

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}
