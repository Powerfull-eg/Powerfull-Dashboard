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
        Schema::create('shops_saves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->refrence("users")->on("id");
            $table->unsignedBigInteger("shop_id")->refrence("shops")->on("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_saves');
    }
};
