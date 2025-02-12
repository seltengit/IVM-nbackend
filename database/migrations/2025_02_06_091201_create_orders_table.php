<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Human-readable order reference
            $table->string('customer_name'); // Full name of the customer
            $table->string('customer_contact'); // Customer phone/email
            $table->text('shipping_address'); // Shipping address
            $table->text('billing_address')->nullable(); // Billing address (if different)

            $table->date('order_date'); // Date when order was placed
            $table->date('expected_delivery_date')->nullable(); // Estimated delivery date

            $table->decimal('total_order_amount', 10, 2)->default(0); // Total cost of order
            $table->decimal('amount_paid', 10, 2)->default(0); // Amount paid
            $table->decimal('pending_amount', 10, 2)->storedAs('total_order_amount - amount_paid'); // Auto-calculated pending amount

            $table->enum('payment_status', ['Pending', 'Partially Paid', 'Fully Paid'])->default('Pending'); // Payment status

            $table->enum('order_status', ['Pending', 'Confirmed', 'Cancelled', 'Completed'])->default('Pending'); // Order status
            $table->text('order_notes')->nullable(); // Order history/notes

            $table->unsignedBigInteger('created_by')->nullable(); // User who created the order
            $table->unsignedBigInteger('updated_by')->nullable(); // User who last updated the order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
