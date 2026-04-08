<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    
    protected $fillable = [
        'no_penjualan',
        'user_id',
        'tanggal_penjualan',
        'total_jumlah',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
