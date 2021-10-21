<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->boolean('active')->default(true);
            $table->string('name', 100);
            $table->jsonb('product_ids');
            $table->date('expire_date');
            $table->text('description')->nullable();
            $table->enum('discount_type',\App\Enums\SaleTypeEnum::toArray())->nullable();
            $table->decimal('discount_amount', 22, 4)->default(0);
            $table->unsignedDouble('value')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('creator_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
