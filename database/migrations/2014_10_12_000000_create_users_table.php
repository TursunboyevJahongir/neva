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
            $table->string('login')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('birthday')->nullable();
            $table->string('interest')->nullable();
            $table->jsonb('kids')->nullable();
            $table->string('coupon')->nullable();
            $table->string('password');
            $table->unsignedInteger('verify_code')->default(12345);

            $table->foreignId('role_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
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
