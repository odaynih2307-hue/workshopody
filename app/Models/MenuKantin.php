<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuKantin extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    protected $fillable = ['idvendor', 'nama_menu', 'harga', 'path_gambar'];

    public function vendor()
    {
        return $this->belongsTo(VendorKantin::class, 'idvendor', 'idvendor');
    }
}
