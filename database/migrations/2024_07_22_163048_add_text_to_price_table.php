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
        Schema::table('prices', function (Blueprint $table) {
            $table->string('app_description_ar')->nullable();
            $table->text('app_description_detailed_ar')->nullable();
            $table->string('app_description_en')->nullable();
            $table->text('app_description_detailed_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price', function (Blueprint $table) {
            //
        });
    }
};
