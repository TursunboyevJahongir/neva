<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name');
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('users');
            $table->jsonb('send_to');
            $table->string('groups');
           // $table->enum('type',[]);
            $table->string('link');
            $table->unsignedBigInteger('element');
            $table->boolean('read')->default(false);
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
        Schema::dropIfExists('notifications');
    }
}
