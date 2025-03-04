<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_lineitems', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_code');

            $table->integer('quantity');


            $table->foreignId('purchaseorder_id')->constrained('purchase_orders')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_lineitems');
    }
};
