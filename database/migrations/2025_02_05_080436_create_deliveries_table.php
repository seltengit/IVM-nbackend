<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_phone');
            $table->string('driver_name');
            $table->string('driver_vehicle_no');
            $table->string('driver_phone');
            $table->boolean('pending')->default(false);
            $table->enum('reason', ['In sufficient products', 'Delivery in person', 'Payment pending'])->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
