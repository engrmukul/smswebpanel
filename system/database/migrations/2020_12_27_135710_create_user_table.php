<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user_group');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address', '255');
            $table->string('mobile', '20');
            $table->dateTime('last_login_time')->nullable();
            $table->enum('status', [
                'ACTIVE',
                'INACTIVE',
                'PENDING'
            ]);
            $table->string('photo')->nullable();
            $table->integer('tps')->default(0);
            $table->enum('dipping', [
                'Active',
                'Inactive'
            ])->default('Inactive');
            $table->integer('created_by')->default(0);
            $table->string('APIKEY')->nullable();
            $table->enum('billing_type', [
                'prepaid',
                'postpaid'
            ])->default('prepaid');
            $table->decimal('mrc_otc',30, 6)->default(0);
            $table->integer('duration_validity')->default(30);
            $table->integer('bill_start')->default(0);
            $table->integer('reseller_id', false, true)->nullable();
            $table->string('assign_user_id')->nullable();
            $table->integer('sms_rate_id')->nullable();
            $table->integer('email_rate_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('user');
    }
}
