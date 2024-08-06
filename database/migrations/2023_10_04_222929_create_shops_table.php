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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->char("name");
            $table->char("phone")->nullable();
            $table->char("provider_id");
            $table->char("icon")->nullable();
            $table->char("logo")->nullable();
            $table->json("images")->nullable();
            $table->char("address")->nullable();
            $table->char("city")->nullable();
            $table->char("governorate")->nullable();
            $table->float("location_latitude",8)->nullable();
            $table->float("location_longitude",8)->nullable();
            $table->integer("created_by")->references('id')->on('admins')->nullable();
            $table->integer("updated_by")->references('id')->on('admins')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};