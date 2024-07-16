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
        Schema::create('gift_users', function (Blueprint $table) {
            $table->id();
            $table->integer("gift_id");
            $table->integer("user_id");
            $table->char("shop_id")->nullable();
            $table->char("shop_name")->nullable();
            $table->char("code");
            $table->timestamp("used_at")->nullable();
            $table->timestamp("expire")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_users');
    }
};
