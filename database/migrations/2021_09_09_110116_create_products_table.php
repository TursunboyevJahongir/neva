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
            $table->jsonb('description')->nullable(); // translatable
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->foreignId('shop_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->jsonb('collection_ids')->nullable();
            $table->jsonb('product_attributes')->nullable();
            $table->double('rating')->default(0)->comment("ratings tablitsadan avg olinib yozib qo'yiladi");
            $table->unsignedBigInteger('quantity')->default(0);
            $table->unsignedDouble('min_old_price')->nullable()->comment('voretionlar ichidagi eng arzon tovarning old price');
            $table->unsignedDouble('min_price')->default(0)->comment('voretionlar ichidagi eng arzon price');
            $table->unsignedDouble('max_price')->default(0)->comment('voretionlar ichidagi eng qimmat price');
            $table->unsignedInteger('max_percent')->nullable();
            $table->bigInteger('position')->nullable();
            $table->string('tag')->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
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
