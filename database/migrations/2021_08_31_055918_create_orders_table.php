<?php

use App\Models\Order;
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
            $table->foreignId('user_id')
                ->constrained();
            $table->foreignId('address_id')
                ->constrained();
//            $table->foreignId('user_coupon_id')
//                ->constrained();
            $table->unsignedInteger('quantity');
            $table->unsignedDouble('total_price');
            $table->unsignedDouble('price_delivery');
            $table->string('delivery');
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', \App\Enums\OrderStatusEnum::toArray())->default(\App\Enums\OrderStatusEnum::NEW);
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
