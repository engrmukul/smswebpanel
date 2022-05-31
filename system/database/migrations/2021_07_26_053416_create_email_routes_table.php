<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id', false, true)->nullable();
            $table->string('source_domain')->nullable();
            $table->integer('email_service_provider_id', false, true);
            $table->decimal('cost', 10, 6)->default(0)->nullable();
            $table->decimal('success_rate', 4, 2)->default(0)->nullable();
            $table->enum('status', [
                'Active',
                'Inactive'
            ])->default('Active');
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('email_routes');
    }
}
