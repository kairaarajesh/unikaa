<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration');
            $table->string('fees');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('max_student');
            $table->string('batch');
             $table->string('type');
            $table->string('course');
            $table->unsignedBigInteger('staff_management_id')->nullable();
            $table->foreign('staff_management_id')->references('id')->on('staff_managements')->onDelete('SET NULL');
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
        Schema::dropIfExists('courses');
    }
}