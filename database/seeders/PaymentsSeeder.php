<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            "user_id" => 1,
            "response_data" => '{
                "apiVersion": 2,
                "apiVersionMinor": 0,
                "paymentMethodData": {
                  "type": "CARD",
                  "description": "Visa •••• 1234",
                  "info": {
                    "cardNetwork": "VISA",
                    "cardDetails": "1234"
                  },
                  "tokenizationData": {
                    "type": "PAYMENT_GATEWAY",
                    "token": "examplePaymentMethodToken"
                  }
                }
              }',
            "card_details" => "{
                number :'1234123412341234',
                type: 'VISA'
                expiry_date: '06/24',
            }",
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }
}
