<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('short_name', 20);
            $table->string('prefix', 10);
            $table->tinyInteger('country_id');
            $table->tinyInteger('created_by');
            $table->tinyInteger('updated_by');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->string('ton', 50)->nullable();
            $table->string('npi', 50)->nullable();
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
        Schema::dropIfExists('operator');
    }
}
