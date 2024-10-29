<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturedProductsSeeder extends Seeder
{
    public function run()
    {
        $candles = [
            ['name' => 'Lavender Bliss', 'image' => 'featured_products/candle1.jpg'],
            ['name' => 'Vanilla Dream', 'image' => 'featured_products/candle2.jpg'],
            ['name' => 'Citrus Sunrise', 'image' => 'featured_products/candle3.jpg'],
            ['name' => 'Rose Elegance', 'image' => 'featured_products/candle4.jpg'],
            ['name' => 'Jasmine Whisper', 'image' => 'featured_products/candle5.jpg'],
            ['name' => 'Minty Fresh', 'image' => 'featured_products/candle6.jpg'],
            ['name' => 'Cedarwood Calm', 'image' => 'featured_products/candle7.jpg'],
            ['name' => 'Amber Spice', 'image' => 'featured_products/candle8.jpg'],
            ['name' => 'Sweet Pea', 'image' => 'featured_products/candle9.jpg'],
        ];

        foreach ($candles as $candle) {
            DB::table('featured_products')->insert($candle);
        }
    }
}
