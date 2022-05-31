<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_service_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_provider');
            $table->enum('provider_type', ['SMTP', 'HTTP']);
            $table->string('url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 150)->nullable();
            $table->string('port', 20)->nullable();
            $table->enum('tls', ['Yes', 'No'])->nullable();
            $table->integer('tps')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'INACTIVE'])->default('PENDING');
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
        Schema::dropIfExists('email_service_providers');
    }
}
