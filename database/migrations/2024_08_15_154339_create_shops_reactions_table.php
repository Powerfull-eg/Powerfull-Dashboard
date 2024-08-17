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
        Schema::create('shops_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->enum('reaction',['like', 'dislike','love','angry','sad','surprised'])->default('like');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_reactions');
    }
};
