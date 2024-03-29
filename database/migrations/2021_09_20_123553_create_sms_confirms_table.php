<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_confirms', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('code');
            $table->integer('try_count')->default(0);
            $table->integer('resend_count')->default(0);
            $table->dateTime('expired_at');
            $table->dateTime('unblocked_at')->nullable();
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
        Schema::dropIfExists('sms_confirms');
    }
}
