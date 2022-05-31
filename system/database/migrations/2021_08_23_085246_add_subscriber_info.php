<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriberInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->boolean('subscribed')->default(true)->after('status');
            $table->string('remarks')->nullable()->after('subscribed');
            $table->datetime('unsubscribe_date')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('subscribed');
            $table->dropColumn('remarks');
            $table->dropColumn('unsubscribe_date');
        });
    }
}
