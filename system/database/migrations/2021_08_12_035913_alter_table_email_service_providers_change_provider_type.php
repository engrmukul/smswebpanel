<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEmailServiceProvidersChangeProviderType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE email_service_providers CHANGE provider_type provider_type ENUM('SMTP', 'HTTP', 'SDK')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE email_service_providers CHANGE provider_type provider_type ENUM('SMTP', 'HTTP')");
    }
}
