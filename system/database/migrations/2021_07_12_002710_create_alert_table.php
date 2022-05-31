<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('message');
            $table->string('alert_type', 30);
            $table->enum('status', [
                'Queued',
                'Sent'
            ]);
            $table->dateTime('report_time')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP(0)'));
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
        Schema::dropIfExists('alert');
    }
}
