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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->char("inet_id")->unique();
            $table->enum("status",["Online","Offline"]);
            $table->integer("signal_value");
            $table->char("type");
            $table->integer("merchant_id")->references('id')->on('merchants');
            $table->integer("slots");
            $table->integer("rentable_slots")->default(0);
            $table->integer("return_slots");
            $table->integer("fault_slots")->default(0);
            $table->char("internet_card")->nullable();
            $table->char("device_ip");
            $table->char("server_ip");
            $table->integer("port");
            $table->enum("authorize",["authorized","notAuthorized"])->default("notAuthorized");
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
        Schema::dropIfExists('stations');
    }
};
