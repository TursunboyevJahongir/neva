<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('product_properties');

            $table->jsonb('combs_attributes')->nullable()->comment('комбинация атрибутов');
            $table->unsignedInteger('quantity');
            $table->unsignedDouble('old_price')->nullable();
            $table->unsignedDouble('percent')->nullable();
            $table->unsignedDouble('price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
