<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('album_id')->nullable()->index();
            // album_id = UUID yang sama untuk foto yang diupload bersamaan
            $table->string('caption');
            $table->text('description')->nullable();
            $table->enum('status', ['public', 'private'])->default('public');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};