<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Lavender Bliss',
                'image' => 'images/mostSold1.jpg', // Full path to the image
                'price' => 15.99,
                'description' => 'A soothing lavender candle, perfect for relaxation.',
                'category_id' => 1 // Assuming 'Relaxation' category
            ],
            [
                'name' => 'Vanilla Dream',
                'image' => 'images/candle2.jpg', // Full path to the image
                'price' => 14.99,
                'description' => 'A warm vanilla candle that brings comfort to any room.',
                'category_id' => 2 // Assuming 'Comfort' category
            ],
            [
                'name' => 'Citrus Sunrise',
                'image' => 'images/mostSold2.jpg', // Full path to the image
                'price' => 13.99,
                'description' => 'A refreshing citrus scent to energize your day.',
                'category_id' => 1 // Assuming 'Energy' category
            ],
            [
                'name' => 'Ocean Breeze',
                'image' => 'images/candle4.jpg', // Full path to the image
                'price' => 16.99,
                'description' => 'A crisp ocean scent to freshen up your space.',
                'category_id' => 2 // Assuming 'Freshness' category
            ],
            [
                'name' => 'Rose Garden',
                'image' => 'images/mostSold5.jpg', // Full path to the image
                'price' => 18.50,
                'description' => 'A floral blend of roses, bringing the essence of a blooming garden to your space.',
                'category_id' => 1 // Assuming 'Floral' category
            ],
            [
                'name' => 'Spiced Apple',
                'image' => 'images/mostSold6.jpg', // Full path to the image
                'price' => 12.99,
                'description' => 'A cozy scent of spiced apples, perfect for autumn evenings.',
                'category_id' => 2 // Assuming 'Autumn' category
            ],
            [
                'name' => 'Eucalyptus Mint',
                'image' => 'images/mostliked1.jpg', // Full path to the image
                'price' => 17.99,
                'description' => 'A refreshing eucalyptus and mint blend for rejuvenation.',
                'category_id' => 1 // Assuming 'Relaxation' category
            ],
            [
                'name' => 'Pumpkin Spice',
                'image' => 'images/mostliked2.jpg', // Full path to the image
                'price' => 15.99,
                'description' => 'A comforting aroma of pumpkin and spices, perfect for fall.',
                'category_id' => 2 // Assuming 'Autumn' category
            ],
            [
                'name' => 'Tropical Paradise',
                'image' => 'images/mostliked3.jpg', // Full path to the image
                'price' => 19.99,
                'description' => 'A fruity and floral mix to transport you to a tropical island.',
                'category_id' => 1 // Assuming 'Tropical' category
            ],
            [
                'name' => 'Sandalwood Serenity',
                'image' => 'images/candle5.jpg', // Full path to the image
                'price' => 20.99,
                'description' => 'An earthy, warm sandalwood scent to bring calm to your space.',
                'category_id' => 2 // Assuming 'Earthy' category
            ],

            [
                'name' => 'Sandalwood Serenity',
                'image' => 'images/candle7.jpg', // Full path to the image
                'price' => 20.99,
                'description' => 'An earthy, warm sandalwood scent to bring calm to your space.',
                'category_id' => 2 // Assuming 'Earthy' category
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }

}
