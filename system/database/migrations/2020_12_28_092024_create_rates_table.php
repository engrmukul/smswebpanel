<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('rate_name');
            $table->decimal('buying_masking_rate', 10, 6)->default(0)->nullable();
            $table->decimal('selling_masking_rate', 10, 6)->default(0)->nullable();
            $table->decimal('buying_nonmasking_rate', 10, 6)->default(0)->nullable();
            $table->decimal('selling_nonmasking_rate', 10, 6)->default(0)->nullable();
            $table->decimal('email_rate', 10, 6)->default(0)->nullable();
            $table->integer('created_by')->default(0);
            $table->enum('rate_type', ['sms', 'email'])->nullable();
            $table->integer('reseller_id', false, true)->nullable();
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
        Schema::dropIfExists('rates');
    }
}
