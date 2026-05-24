<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            // yang follow
            $table->foreignId('following_id')->constrained('users')->cascadeOnDelete();
            // yang di-follow
            $table->unique(['follower_id', 'following_id']);
            // tidak bisa follow orang yang sama 2x
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};