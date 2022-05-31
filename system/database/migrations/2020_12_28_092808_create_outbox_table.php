<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('outbox', function (Blueprint $table) {
            $table->id();
            $table->string('srcmn', 15)->nullable();
            $table->string('mask', 15)->nullable();
            $table->string('destmn', 15)->nullable();
            $table->text('message')->nullable();
            $table->integer('country_code')->nullable();
            $table->integer('operator_prefix')->nullable();
            $table->enum('status', ['Failed','Delivered','Sent','Processing','Queue','Hold'])->default('Queue');
            $table->dateTime('write_time')->nullable();
            $table->dateTime('sent_time')->nullable();
            $table->string('ton', 50)->nullable();
            $table->string('npi', 50)->nullable();
            $table->enum('message_type', ['text', 'binary', '1'])->nullable()->default('text');
            $table->tinyInteger('is_unicode');
            $table->integer('smscount')->nullable();
            $table->string('esm_class', 50)->nullable();
            $table->string('data_coding', 50)->nullable();
            $table->integer('reference_id')->nullable();
            $table->dateTime('last_updated')->nullable();
            $table->dateTime('schedule_time')->nullable();
            $table->integer('retry_count')->nullable();
            $table->bigInteger('user_id');
            $table->text('remarks')->nullable();
            $table->string('uuid', 16)->charset('binary')->nullable()->unique();
            $table->integer('priority')->nullable()->default(0);
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
        Schema::dropIfExists('outbox');
    }
}
