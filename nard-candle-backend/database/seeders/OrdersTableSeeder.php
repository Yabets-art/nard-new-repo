<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'total_amount' => 31.98,
                'tx_ref' => Str::random(20),
                'status' => 'completed',
                'payment_method' => 'chapa',
                'customer_email' => 'admin@admin.com',
                'customer_name' => 'Admin User',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'total_amount' => 10.99,
                'tx_ref' => Str::random(20),
                'status' => 'completed',
                'payment_method' => 'chapa',
                'customer_email' => 'admin@admin.com',
                'customer_name' => 'Admin User',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
