<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained();
            $table->foreignId('shop_id')
                ->constrained();

            $table->foreignId('product_variation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // order item

            $table->string('sku');
            $table->unsignedInteger('quantity');
            $table->unsignedDouble('sum');
            $table->unsignedDouble('price');
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
        Schema::dropIfExists('order_items');
    }
}
