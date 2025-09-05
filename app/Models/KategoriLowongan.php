<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriLowongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
    ];

    // Relasi ke lowongan (jika 1 kategori banyak lowongan)
    public function lowongans()
    {
        return $this->hasMany(Lowongan::class, 'kategori_id');
    }
}
