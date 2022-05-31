<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id', false, true)->nullable();
            $table->enum('has_mask', ['2','1','0'])->default(2);
            $table->string('operator_prefix')->nullable();
            $table->integer('channel_id', false, true);
            $table->decimal('cost', 10, 6)->default(0)->nullable();
            $table->decimal('success_rate', 4, 2)->default(0)->nullable();
            $table->string('default_mask')->nullable();
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
        Schema::dropIfExists('routes');
    }
}
