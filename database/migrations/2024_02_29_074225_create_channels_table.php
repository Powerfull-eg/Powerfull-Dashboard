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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("type")->comment("1 => SMS, 2 => Email, 3 => Push Notification");
            $table->text("content");
            $table->text("reciever");
            $table->text("response")->nullable();
            $table->tinyInteger("status")->comment("1 => success, 2 => fail");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
