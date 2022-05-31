<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('orderid')->nullable();
            $table->enum('source', ['WEB', 'API'])->default('WEB')->nullable();
            $table->text('message')->nullable();
            $table->string('domain')->nullable();
            $table->string('from_email')->nullable();
            $table->text('recipients')->nullable();
            $table->string('group_id', 150)->nullable();
            $table->dateTime('date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->enum('status', ['Sending','Sent','Draft','Failed'])->default('Draft');
            $table->string('IP')->default('Unknown');
            $table->enum('email_type', ['sendEmail', 'campaignEmail']);
            $table->dateTime('schedule_date_time')->nullable();
            $table->string('search_param', 255)->nullable();
            $table->string('error')->nullable();
            $table->string('file', 255)->nullable();
            $table->integer('priority')->nullable();
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
        Schema::dropIfExists('sent_emails');
    }
}
