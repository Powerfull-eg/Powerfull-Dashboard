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
        Schema::table('operations', function (Blueprint $table) {
            $table->integer('shop_id')->nullable()->after('user_id')->references('id')->on('shops');
        });
        // Update shop id column with shop id column value in devices table where station_id is primary key in devices table
        DB::update('UPDATE operations SET shop_id = (SELECT shop_id FROM devices WHERE operations.station_id = devices.device_id);');        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            //
        });
    }
};
