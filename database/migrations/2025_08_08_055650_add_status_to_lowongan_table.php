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
        Schema::table('lowongans', function (Blueprint $table) {
            $table->string('status')->default('Active')->after('waktu_posting');
            $table->string('dekskripsi')->default('')->after('posisi');
        });
    }

    public function down(): void
    {
        Schema::table('lowongans', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn(columns: 'dekskripsi');
        });
    }
};
