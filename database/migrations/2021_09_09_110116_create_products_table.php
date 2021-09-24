<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->jsonb('name');
            $table->jsonb('content')->nullable(); // translatable
            $table->string('sku')->unique()->nullable();
            $table->string('slug')->unique();
            $table->foreignId('shop_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->jsonb('collection_ids')->nullable();
            $table->jsonb('product_attribute_ids')->nullable();
            $table->double('rating')->default(0)->comment("ratings tablitsadan avg olinib yozib qo'yiladi");
            $table->unsignedBigInteger('quantity')->default(0);
            $table->unsignedBigInteger('min_price')->default(0);
            $table->unsignedBigInteger('max_price')->default(0);
            $table->bigInteger('position')->nullable();
            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('products');
    }
}
