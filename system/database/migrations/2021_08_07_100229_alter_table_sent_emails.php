<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSentEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->string('subject')->after('from_email');
            $table->string('attachment')->nullable()->default(null)->after('from_email');
            $table->integer('template_id')->nullable()->default(null)->after('from_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->dropColumn('subject');
            $table->dropColumn('attachment');
            $table->dropColumn('template_id');
        });
    }
}
