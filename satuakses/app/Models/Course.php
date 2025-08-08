<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'deskripsi', 'kategori', 'tingkat', 'tanggal_mulai'];
    protected $casts = [
        'tanggal_mulai' => 'datetime',
    ];
}
