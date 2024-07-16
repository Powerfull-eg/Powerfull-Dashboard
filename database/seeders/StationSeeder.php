<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stations')->insert([
            'inet_id' => Str::random(6),
            'status' => "Online",
            'signal_value' => "19",
            'type' => "6-slot Cabinet",
            'merchant_id' => 1,
            'slots' => 6,
            'return_slots' => 6,
            'internet_card' => "89851958261098485432",
            'device_ip' => "103.135.144.25",
            'server_ip' => "172.21.86.168",
            'port' => "12402",
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
