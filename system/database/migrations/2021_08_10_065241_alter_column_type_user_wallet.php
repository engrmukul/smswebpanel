<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnTypeUserWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallet', function (Blueprint $table) {
            $table->integer('non_masking_balance')->change();
            $table->integer('masking_balance')->change();
            $table->integer('email_balance')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wallet', function (Blueprint $table) {
            $table->string('non_masking_balance')->change();
            $table->string('masking_balance')->change();
            $table->string('email_balance')->change();
        });
    }
}
