<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('email');
            $table->bigInteger('number');
            $table->string('gender');
            $table->string('dob');
            $table->string('joining_date');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->bigInteger('pin_code');
            $table->string('emergency_name');
            $table->bigInteger('emergency_number');
            $table->bigInteger('aadhar_card');
            $table->string('fees_status');
            $table->string('payment_history');
            $table->time('batch_timing');
            $table->unsignedBigInteger('staff_management_id')->nullable();
            $table->foreign('staff_management_id')->references('id')->on('staff_managements')->onDelete('SET NULL');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('SET NULL');
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
        Schema::dropIfExists('students');
    }
}
