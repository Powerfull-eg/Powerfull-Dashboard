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
        Schema::create('voucher_order', function (Blueprint $table) {
            $table->id();
            $table->integer("order_id")->references('id')->on('operations')->nullable();
            $table->integer("voucher_id")->references('id')->on('vouchers');
            $table->integer("user_id")->references('id')->on('users');
            $table->timestamp("added_at")->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
