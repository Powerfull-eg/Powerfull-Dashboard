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
        Schema::create('users_account_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->refrence("users")->on("id");
            $table->string("action");
            $table->text("description")->nullable();
            $table->text("notes")->nullable();
            $table->unsignedBigInteger("done_by")->refrence("admins")->on("id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_account_histories');
    }
};
