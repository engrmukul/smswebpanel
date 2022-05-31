<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSentEmailsChangeEmailType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE sent_emails CHANGE email_type email_type ENUM('sendEmail', 'groupEmail', 'campaignEmail')");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE sent_emails CHANGE email_type email_type ENUM('sendEmail', 'campaignEmail')");
    }
}
