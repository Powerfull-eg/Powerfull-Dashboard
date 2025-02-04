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
        Schema::create('shops_admins', function (Blueprint $table) {
            $table->id();
            $table->integer("shop_id")->refrence("shops")->on("id");
            $table->integer("admin_id")->refrence("admins")->on("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_admins');
    }
};
