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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User relationship
            $table->decimal('total', 10, 2); // Total price of the order
            $table->integer('amount_of_products'); // Number of products in the order
            $table->timestamp('purchase_date'); // Date of purchase
            $table->enum('payment_method', ['credit_card', 'paypal', 'bank_transfer'])->default('credit_card'); // Payment method
            $table->enum('order_status', ['pending', 'shipped', 'delivered', 'canceled'])->default('pending'); // Status of the order
            $table->decimal('discount_applied', 10, 2)->default(0); // Discount applied on the order
            $table->decimal('shipping_cost', 10, 2)->default(0); // Shipping cost
            $table->timestamp('delivery_date')->nullable(); // Date the order was delivered
            $table->string('product_category')->nullable(); // Category of products in the order
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
