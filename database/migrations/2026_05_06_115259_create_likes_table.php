<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'photo_id']);
            // 1 user hanya bisa like 1 foto sekali
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};