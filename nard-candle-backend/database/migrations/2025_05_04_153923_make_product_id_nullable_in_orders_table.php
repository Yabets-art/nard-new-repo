<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist before modifying
        if (Schema::hasColumn('orders', 'product_id')) {
            // Use raw SQL to modify column constraint
            DB::statement('ALTER TABLE orders MODIFY product_id BIGINT UNSIGNED NULL');
        }
        
        if (Schema::hasColumn('orders', 'quantity')) {
            DB::statement('ALTER TABLE orders MODIFY quantity INT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if columns exist before modifying
        if (Schema::hasColumn('orders', 'product_id')) {
            DB::statement('ALTER TABLE orders MODIFY product_id BIGINT UNSIGNED NOT NULL');
        }
        
        if (Schema::hasColumn('orders', 'quantity')) {
            DB::statement('ALTER TABLE orders MODIFY quantity INT NOT NULL');
        }
    }
};
