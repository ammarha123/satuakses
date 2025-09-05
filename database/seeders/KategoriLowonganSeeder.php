<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\KategoriLowongan;

class KategoriLowonganSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Administrasi',
            'Desain Grafis',
            'Wirausaha',
            'Pendidikan',
            'IT & Teknologi',
            'Keuangan',
            'Kesehatan',
            'Layanan Pelanggan',
        ];

        foreach ($categories as $kategori) {
            KategoriLowongan::create([
                'nama' => $kategori,
                'slug' => Str::slug($kategori),
            ]);
        }
    }
}
