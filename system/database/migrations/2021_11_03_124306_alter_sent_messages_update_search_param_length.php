<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSentMessagesUpdateSearchParamLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sentmessages', function (Blueprint $table) {
            $table->text('search_param')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sentmessages', function (Blueprint $table) {
            $table->string('search_param')->nullable()->change();
        });
    }
}
