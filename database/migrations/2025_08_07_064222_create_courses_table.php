<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori');
            $table->string('tingkat');
            $table->text('deskripsi');
            $table->date('tanggal_mulai')->nullable();
            $table->string('durasi')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->boolean('sertifikat_diberikan')->default(true);
            $table->integer('kuota')->nullable();
            $table->string('status')->default('Active');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
