
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity_required');
            $table->integer('quantity_delivered');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('line_items');
    }
};
