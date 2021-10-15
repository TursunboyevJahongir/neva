<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('variation_id');
            $table->foreign('variation_id')->references('id')->on('product_variations');

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
        Schema::dropIfExists('variation_properties');
    }
}
