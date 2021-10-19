<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->BigInteger('object_id')->nullable();
            $table->enum('coupon_type',\App\Enums\CouponTypeEnum::toArray())->nullable();
            $table->string('code');
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('sale_type',\App\Enums\SaleTypeEnum::toArray())->comment('price,percent');
            $table->unsignedDouble('value');
            $table->unsignedBigInteger('count')->nullable()->comment('ishlash soni');
            $table->unsignedDouble('price')->nullable()->comment('minimum summa');
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('coupons');
    }
}
