<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('reseller_name', 30)->unique();
            $table->decimal('available_balance', 30, 6)->default(0);
            $table->integer('tps')->default(0);
            $table->decimal('due')->default(0);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('thana')->nullable();
            $table->string('district')->nullable();
            $table->integer('sms_rate_id')->nullable();
            $table->integer('email_rate_id')->nullable();
            $table->enum('status', [
                'ACTIVE',
                'INACTIVE',
                'PENDING'
            ]);
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
        Schema::dropIfExists('resellers');
    }
}
