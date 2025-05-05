<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('trainees', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('address');
        $table->string('phone_number');
        $table->string('email')->unique();
        $table->string('educational_status');
        $table->string('training_type');
        $table->date('training_day');
        $table->enum('status', ['waiting', 'paid'])->default('waiting');
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainees');
    }
}
