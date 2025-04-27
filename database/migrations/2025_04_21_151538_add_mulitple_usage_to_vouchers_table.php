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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->boolean('multiple_usage')->default(false)->after('value');
            $table->integer('usage_count')->default(0)->after('multiple_usage');
            $table->unsignedBigInteger('campaign_id')->nullable()->after('usage_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher', function (Blueprint $table) {
            $table->dropColumn('multiple_usage');
            $table->dropColumn('usage_count');
        });
    }
};
