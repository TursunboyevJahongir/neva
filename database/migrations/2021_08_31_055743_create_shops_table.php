<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->jsonb('name');
            $table->string('slug');
            $table->jsonb('description')->nullable();
            $table->unsignedBigInteger('delivery_price')->default(0);
            $table->string('delivery_time')->default('+2 hours');
            $table->jsonb('work_day');
            $table->time('open')->default('09:00');
            $table->time('close')->default('18:00');
            $table->boolean('pickup')->default(false);
            $table->unsignedInteger('refund')->default(0); // days, 0 is unavailable
            $table->string('merchant_id')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('shops');
    }
}
