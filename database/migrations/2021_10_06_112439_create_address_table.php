<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('apartment')->nullable()->comment('квартира');
            $table->string('storey')->nullable()->comment('этаж');
            $table->string('intercom')->nullable()->comment('домофон');
            $table->string('entrance')->nullable()->comment('подъезд');
            $table->text('landmark')->nullable()->comment('ориентир');

            $table->text('address');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('main_address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
