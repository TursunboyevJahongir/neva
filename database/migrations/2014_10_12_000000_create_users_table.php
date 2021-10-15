<?php

use App\Enums\UserStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->enum('gender', \App\Enums\GenderEnum::toArray())->nullable();
            $table->date('birthday')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('login')->unique()->nullable();
            $table->string('password')->nullable();
//            $table->unsignedBigInteger('district_id')->nullable();
            $table->json('interests')->nullable();
            $table->unsignedBigInteger('main_card')->nullable();
            $table->enum('status', UserStatusEnum::toArray())->default(UserStatusEnum::PENDING);
            $table->string('firebase')->nullable()->comment('firebase push token');
            $table->unsignedBigInteger('main_address_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
