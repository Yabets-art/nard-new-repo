<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('alerts', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // e.g., 'info', 'success', 'warning'
        $table->string('icon'); // e.g., 'fas fa-file-alt'
        $table->string('title')->nullable(); // optional short title
        $table->text('message'); // main alert message
        $table->timestamp('created_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alerts');
    }
}
