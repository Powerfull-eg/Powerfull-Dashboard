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
            $table->string("gateway")->nullable()->after("card_number");
            $table->longText("gateway_response")->nullable()->after("card_number");
        });

        DB::table('cards')->update([
            'gateway_response' => DB::raw('paymob_response')
        ]);

        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('paymob_response');
        });
    

        DB::table('cards')->update(['gateway' => 'paymob']);
        DB::table('settings')->updateOrInsert(['key' => 'payment_gateway'],['key' => 'payment_gateway', 'value' => 'paymob']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->longText("paymob_response")->nullable()->after("card_number");
            $table->dropColumn("gateway");
        });

        DB::table('cards')->update([
            'paymob_response' => DB::raw('gateway_response')
        ]);

        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('gateway_response');
        });
    }
};
