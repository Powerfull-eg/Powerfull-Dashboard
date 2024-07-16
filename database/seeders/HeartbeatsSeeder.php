<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeartbeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('heartbeats')->insert([
            "station_id" => 1,
            "heartbeat_time" => Carbon::now(), 
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }
}
