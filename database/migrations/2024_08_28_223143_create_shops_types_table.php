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
        Schema::create('shops_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            $table->string('type_ar_name');
            $table->string('type_en_name');
            $table->string('type_icon');
            $table->string('access_ar_name');
            $table->string('access_en_name');
            $table->string('access_icon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_types');
    }
};
