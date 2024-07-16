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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->char("station_id");
            $table->char("powerbank_id")->nullable();
            $table->char("tradeNo");
            $table->char("borrowTime")->nullable();
            $table->char("returnTime")->nullable();
            $table->char("borrowSlot")->nullable();
            $table->integer("user_id")->refrence("users")->on("id");
            $table->integer("card_id")->refrence("cards")->on("id");
            $table->integer("payment_id")->refrence("payments")->on("id")->nullable();
            $table->integer("status")->default(0)->comment("0 => new, 1 => running , 2 => Completed"); // 1 => rent , 2 => return 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
