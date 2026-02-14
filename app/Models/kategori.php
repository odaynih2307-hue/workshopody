<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Beritahu Laravel nama tabelnya
    protected $table = 'kategori';

    // Beritahu Laravel bahwa Primary Key-nya bukan 'id'
    protected $primaryKey = 'idkategori';

    // Jika idkategori bukan angka (string), set ini ke false. Tapi jika auto-increment, biarkan true.
    public $incrementing = true;

    protected $fillable = ['nama_kategori'];
}