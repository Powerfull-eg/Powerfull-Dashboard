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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->refrence("users")->on("id");
            $table->text("response_data")->nullable();
            $table->char("type")->nullable();
            $table->text("card_details")->nullable();
            $table->bigInteger("payment_order_id")->nullable();
            $table->text("token")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};