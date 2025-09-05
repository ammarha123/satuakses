<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('perusahaan');
            $table->string('posisi');
            $table->text('dekskripsi')->nullable();
            $table->string('lokasi');
            $table->enum('tipe_pekerjaan', ['Full-time', 'Part-time', 'Remote', 'Hybrid'])->default('Full-time');
            $table->text('persyaratan')->nullable();
            $table->text('fasilitas_disabilitas')->nullable();
            $table->decimal('gaji_min', 10, 2)->nullable();
            $table->decimal('gaji_max', 10, 2)->nullable();
            $table->integer('kuota')->nullable();
            $table->date('batas_lamaran')->nullable();
            $table->timestamp('waktu_posting')->nullable();
            $table->string('status')->default('Active');
            $table->boolean('is_terbuka')->default(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
