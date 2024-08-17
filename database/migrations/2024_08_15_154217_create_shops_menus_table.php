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
        Schema::create('shops_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained();
            $table->string('image');
            $table->enum('is_hero', ['yes', 'no'])->default('no');       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_menus');
    }
};
