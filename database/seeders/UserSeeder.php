<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => "test",
            'last_name' => "user",
            'email' => "user".'@email.com',
            'password' => Hash::make('password'),
            'code' => "+20",
            'phone' => 01011112222,
        ]);
    }
}
