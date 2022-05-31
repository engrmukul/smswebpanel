<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusRemarksToEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->enum('status', [
                'Queue',
                'Sent',
                'Delivered',
                'Opened',
                'Failed'
            ])->default('Sent')->after('write_time');

            $table->string('remarks')->nullable()->after('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('remarks');
        });
    }
}
