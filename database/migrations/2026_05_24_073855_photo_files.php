<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            // Jika foto dihapus, file-nya ikut terhapus dari DB
            $table->string('file_path');
            $table->integer('order')->default(0);
            // urutan tampil di slider
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('photo_files');
    }
};