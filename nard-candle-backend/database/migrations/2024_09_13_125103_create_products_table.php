<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image'); // Path to the product image
            $table->decimal('price', 8, 2); // Price with two decimal places
            $table->text('description'); // Description of the product
            $table->unsignedBigInteger('category_id'); // Foreign key to categories
            $table->timestamps();
            
            // Assuming there's a categories table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
