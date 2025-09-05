<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lowongan_id')->constrained('lowongans')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path')->nullable(); 
            $table->enum('status', ['submitted','reviewed','shortlisted','rejected','hired'])->default('submitted');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id','lowongan_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
