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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Product description (optional)
            $table->decimal('price'); // Product price (up to 10 digits, 2 decimal places)
            $table->integer('stocks')->default(0); // Available stock
            $table->string('unit');
            $table->string('brand');
            $table->string('design');

            $table->string('hsincode');

            $table->string('varient')->default(0);
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->enum('status', ['0', '1'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
