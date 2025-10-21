<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->Increments('id')->unsigned();
                // $table->id();
                $table->string('slug')->unique();
                $table->time('time_in');
                $table->time('time_out');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('schedule_employees')) {
            Schema::create('schedule_employees', function (Blueprint $table) {
                $table->integer('emp_id')->unsigned();
                $table->integer('schedule_id')->unsigned();
                $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
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
        Schema::table('schedule_employees', function (Blueprint $table) {

            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['emp_id']);
           });

        Schema::dropIfExists('schedule_employees');
        Schema::dropIfExists('schedules');
    }
}
