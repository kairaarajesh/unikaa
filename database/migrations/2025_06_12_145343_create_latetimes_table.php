<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatetimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('latetimes')) {
        Schema::create('latetimes', function (Blueprint $table) {
            $table->id();
             $table->integer('emp_id')->unsigned();
            $table->time('duration');
            $table->date('latetime_date');

            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('latetimes', function (Blueprint $table) {
            $table->dropForeign(['emp_id']);
           });
        Schema::dropIfExists('latetimes');
    }
}
