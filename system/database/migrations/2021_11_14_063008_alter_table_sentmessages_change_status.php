<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSentmessagesChangeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE sentmessages CHANGE status status ENUM('Draft', 'Queue', 'Sending','Sent','Failed') NOT NULL DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE sentmessages CHANGE status status ENUM('Sending','Sent','Draft','Failed')");
    }
}
