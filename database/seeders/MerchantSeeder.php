<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('merchants')->insert([
            'name' => Str::random(10),
            'city' => Str::random(1),
            'governorate' => Str::random(1),
            'location' => json_encode(["lat" => "30.033333","lng" => "31.233334"]),
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
