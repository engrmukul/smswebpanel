<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->string('name_en');
            $table->string('name_bn')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('upazilla')->nullable();
            $table->string('blood_group')->nullable();
            $table->integer('user_id', false, true);
            $table->enum('status', ['Inactive', 'Active'])->default('Active');
            $table->integer('reseller_id', false, true)->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
