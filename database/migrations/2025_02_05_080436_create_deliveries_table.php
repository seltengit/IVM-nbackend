<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Reference to Order module
            $table->date('scheduled_delivery_date'); // Planned delivery date
            $table->date('actual_delivery_date')->nullable(); // Actual delivery completion date
            $table->text('delivery_address'); // Destination address
            $table->string('courier_name')->nullable(); // Courier/Delivery partner name
            $table->string('courier_contact')->nullable(); // Contact details of courier service
            $table->string('tracking_number')->nullable(); // Tracking number (if applicable)
            $table->enum('delivery_status', ['Pending', 'In-Transit', 'Delivered', 'Failed'])->default('Pending'); // Delivery status
            $table->text('delivery_notes')->nullable(); // Additional remarks or instructions
            $table->decimal('delivery_charges', 10, 2)->default(0); // Total delivery cost
            $table->decimal('amount_paid', 10, 2)->default(0); // Amount paid for delivery
            $table->decimal('pending_amount', 10, 2)->storedAs('delivery_charges - amount_paid'); // Auto-calculated pending delivery amount
            $table->unsignedBigInteger('created_by')->nullable(); // User who created the record
            $table->unsignedBigInteger('updated_by')->nullable(); // User who last updated the record
            $table->timestamps(); // Created_at & updated_at timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
