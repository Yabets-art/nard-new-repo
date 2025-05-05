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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tx_ref')->unique()->comment('Transaction reference from payment gateway');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending')->comment('pending, processing, completed, cancelled');
            $table->text('order_items')->nullable()->comment('JSON encoded cart items');
            $table->string('payment_method')->default('chapa');
            $table->string('shipping_address')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('notes')->nullable();
            $table->text('checkout_url')->nullable()->comment('Payment gateway checkout URL');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
