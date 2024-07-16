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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            
            $table->string('avatar')->nullable();

            $table->string('email')->unique()->nullable();
            $table->string('google')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fingerprint')->nullable();
            
            $table->string('code')->default('+20');
            $table->integer('phone')->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('otp')->nullable();

            $table->string('password')->default("powerfullegpassword");
            $table->rememberToken();

            $table->integer("updated_by")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
