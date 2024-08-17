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
        Schema::create('shops_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            $table->integer('rate')->nullable();
            $table->string('comment')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('hidden', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_rates');
    }
};
