<?php

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
            $table->string('full_name')->nullable();
            $table->string('phone')->unique();
            $table->enum('gender', \App\Enums\GenderEnum::toArray())->nullable();
            $table->date('birthday')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('login')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('district_id')->nullable();
            $table->string('interests')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', \App\Enums\UserStatusEnum::toArray());
            $table->string('firebase')->nullable()->comment('firebase push token');
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
