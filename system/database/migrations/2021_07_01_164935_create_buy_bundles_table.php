<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_bundles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('masking_balance')->default(0)->nullable();
            $table->string('non_masking_balance')->default(0)->nullable();
            $table->string('email_balance')->default(0)->nullable();
            $table->decimal('masking_rate', 30,3)->default(0)->nullable();
            $table->decimal('non_masking_rate', 30, 3)->default(0)->nullable();
            $table->decimal('email_rate', 30, 3)->default(0)->nullable();
            $table->decimal('total_price', 30, 3)->default(0)->nullable();
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
        Schema::dropIfExists('buy_bundles');
    }
}
