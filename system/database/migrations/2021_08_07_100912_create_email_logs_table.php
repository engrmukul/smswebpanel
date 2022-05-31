<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('domain');
            $table->string('from_email');
            $table->string('to_email');
            $table->string('reference_id');
            $table->string('write_time');
            $table->string('response')->nullable();
            $table->string('delivery_reports')->nullable();
            $table->string('opened')->nullable();
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
        Schema::dropIfExists('email_logs');
    }
}
