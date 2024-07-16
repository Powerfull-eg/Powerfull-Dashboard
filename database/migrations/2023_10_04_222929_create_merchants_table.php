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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->char("name");
            $table->char("logo")->nullable();
            $table->json("images")->nullable();
            $table->char("address")->nullable();
            $table->smallInteger("city");
            $table->smallInteger("governorate");
            $table->json("location");
            $table->integer("created_by")->references('id')->on('admins');
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
        Schema::dropIfExists('merchants');
    }
};
