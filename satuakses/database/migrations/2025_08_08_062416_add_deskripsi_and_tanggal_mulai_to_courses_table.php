<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('tingkat');
            $table->text('deskripsi')->nullable()->after('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('tanggal_mulai');
            $table->dropColumn('deskripsi');
        });
    }
};
