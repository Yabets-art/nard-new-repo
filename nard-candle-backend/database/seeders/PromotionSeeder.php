<?php

namespace Database\Seeders; // Add this namespace

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        Promotion::create([
            'title' => 'New Arrival - Lavender Candles',
            'description' => 'Explore our new Lavender-scented candles for a soothing experience.',
            'image' => 'lavender_candle.jpg',
        ]);

        Promotion::create([
            'title' => 'Summer Sale - 20% Off',
            'description' => 'Get 20% off on all candles this summer season!',
            'image' => 'summer_sale.jpg',
        ]);
    }
}
