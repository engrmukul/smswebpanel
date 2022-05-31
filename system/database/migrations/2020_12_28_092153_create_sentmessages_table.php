<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentmessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sentmessages', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('orderid')->nullable();
            $table->enum('source', ['WEB', 'API'])->default('WEB')->nullable();
            $table->string('mobile_no_column')->nullable();
            $table->text('message')->nullable();
            $table->text('json_data')->nullable();
            $table->string('senderID')->nullable();
            $table->text('recipient')->nullable();
            $table->string('group_id', 150)->nullable();
            $table->dateTime('date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->integer('pages')->nullable()->default(0);
            $table->enum('status', ['Sending','Sent','Draft','Failed'])->default('Draft');
            $table->decimal('units', 10,2)->default(0);
            $table->string('sentFrom')->default('Panel');
            $table->integer('is_mms')->default(0);
            $table->integer('sms_count')->default(0);
            $table->integer('is_unicode')->default(0);
            $table->string('IP')->default('Unknown');
            $table->string('gateway_id')->nullable();
            $table->enum('sms_type', ['sendSms','groupSms','fileSms','DynamicSms', 'campaignSms']);
            $table->dateTime('scheduleDateTime')->nullable();
            $table->string('search_param', 255)->nullable();
            $table->string('error')->nullable();
            $table->string('file', 255)->nullable();
            $table->integer('priority')->nullable();
            $table->enum('blocked_status', ['1','2'])->nullable()->default(NULL)->comment("1 = blocked and 2 = unblocked");
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
        Schema::dropIfExists('sentmessages');
    }
}
