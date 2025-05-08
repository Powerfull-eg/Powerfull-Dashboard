<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->renameColumn("paymob_response", "gateway_response");
        });
    
        Schema::table('cards', function (Blueprint $table) {
            $table->string("gateway")->nullable()->after("card_number");
        });

        DB::table('cards')->update(['gateway' => 'paymob']);
        DB::table('settings')->create(['key' => 'payment_gateway', 'value' => 'paymob']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->renameColumn("gateway_response", "paymob_response");

            $table->dropColumn("gateway");

        });
    }
};
