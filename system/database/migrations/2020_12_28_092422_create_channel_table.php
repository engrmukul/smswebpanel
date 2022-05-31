<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_provider');
            $table->enum('channel_type', ['MAP', 'SMPP', 'HTTP']);
            $table->enum('method', ['POST', 'GET'])->nullable()->default(null);
            $table->text('url')->nullable();
            $table->enum('content_type', ['ARRAY', 'JSON'])->nullable()->default(null);
            $table->text('sms_parameter')->nullable();
            $table->text('balance_url')->nullable();
            $table->text('balance_parameter')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 150)->nullable();
            $table->string('ip', 50)->nullable();
            $table->string('port', 20)->nullable();
            $table->string('account_code', 100)->nullable();
            $table->string('mode', 100)->nullable();
            $table->string('default_mask', 20)->nullable();
            $table->integer('tps')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'INACTIVE'])->default('PENDING');
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
        Schema::dropIfExists('channel');
    }
}
