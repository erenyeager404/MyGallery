<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('photo_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->unique(['photo_id', 'tag_id']);
            // 1 foto tidak bisa punya tag yang sama 2x
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('photo_tag');
        Schema::dropIfExists('tags');
    }
};