<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan',
        'kategori',
        'lokasi',
        'posisi',
        'dekskripsi',
        'waktu_posting',
        'status'
    ];

    protected $casts = [
        'waktu_posting' => 'datetime',
    ];
}
