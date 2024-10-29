<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'total_price' => 31.98
            ],
            [
                'user_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'total_price' => 10.99
            ],
            // Add more sample orders as needed
        ]);
    }
}
