<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOndelete();

            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('price_delivery');
            $table->string('delivery');
            $table->string('status')->default(Order::STATUS_NEW);
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('comment')->nullable();

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->cascadeOnDelete();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
