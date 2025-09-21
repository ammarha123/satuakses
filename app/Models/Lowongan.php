<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan',
        'kategori_id',
        'lokasi',
        'posisi',
        'dekskripsi',
        'slug',
        'tipe_pekerjaan',
        'persyaratan',
        'fasilitas_disabilitas',
        'gaji_min',
        'gaji_max',
        'kuota',
        'batas_lamaran',
        'waktu_posting',
        'status',
        'is_terbuka'
    ];

    protected $casts = [
        'waktu_posting' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriLowongan::class, 'kategori_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
