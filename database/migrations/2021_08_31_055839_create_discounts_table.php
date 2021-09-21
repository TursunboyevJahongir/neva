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
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->string('name', 100);
            $table->string('coupon');
            $table->date('expire_date');
            $table->text('description')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 22, 4)->default(0);
            $table->unsignedBigInteger('discount_price')->nullable();
            $table->unsignedBigInteger('discount_percent')->nullable();
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
        Schema::dropIfExists('discounts');
    }
}
