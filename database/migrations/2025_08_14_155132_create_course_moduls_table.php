<?php
// database/migrations/2025_08_13_000002_create_course_modules_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();   // deskripsi/rangkuman materi
            $table->string('video_url')->nullable(); // link youtube/vimeo atau HLS
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });

        Schema::create('module_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->string('original_name');
            $table->string('file_path');     // disimpan di storage/app/public/…
            $table->unsignedInteger('size')->nullable(); // KB
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('module_attachments');
        Schema::dropIfExists('course_modules');
    }
};
