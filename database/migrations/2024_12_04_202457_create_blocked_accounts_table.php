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
        Schema::create('blocked_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->refrence("users")->on("id");
            $table->unsignedBigInteger('blocked_by')->refrence("users")->on("id");
            $table->string('reason')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_accounts');
    }
};
