<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategoriesTableSeeder::class,
            ProductSeeder::class,
            OrdersTableSeeder::class,
            FeaturedProductsSeeder::class,
            TestUserSeeder::class,
        ]);
    }
}
