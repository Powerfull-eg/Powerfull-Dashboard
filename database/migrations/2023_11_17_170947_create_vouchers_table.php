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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->integer("user_id")->default(0);
            $table->tinyInteger("type")->default(0)->comment("0 => percentage, 1 => amount");
            $table->integer("value");
            $table->integer("min_amount")->default(0);
            $table->integer("max_discount")->nullable();
            $table->timestamp("from")->useCurrent();
            $table->timestamp("to")->useCurrent();
            $table->text("image")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};