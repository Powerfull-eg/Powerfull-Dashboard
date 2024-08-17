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
        Schema::create('shops_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained();
            $table->unsignedBigInteger('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            $table->json('data');
            $table->string('type_ar');
            $table->string('type_en');
            $table->string('logo')->nullable();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('place_access_ar')->nullable();
            $table->string('place_access_en')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_data');
    }
};
