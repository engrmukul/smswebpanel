<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSentEmailsChangeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE sent_emails CHANGE status status ENUM('Draft', 'Queue', 'Sending','Sent','Failed') NOT NULL DEFAULT 'Draft'");
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->longText('attachment')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE sent_emails CHANGE status status ENUM('Sending','Sent','Draft','Failed')");
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
}
