<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_history', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id', false, true)->nullable();
            $table->integer('reseller_id', false, true)->nullable();
            $table->string('deposit_by');
            $table->string('deposit_amount')->default(0)->nullable();
            $table->enum('status', ['Pending', 'Approved'])->default('Pending');
            $table->string('comment')->nullable();
            $table->dateTime('approved_date')->nullable();
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
        Schema::dropIfExists('deposit_history');
    }
}
