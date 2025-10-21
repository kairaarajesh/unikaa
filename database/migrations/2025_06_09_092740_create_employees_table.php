<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
              Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('employee_email');
            $table->bigInteger('employee_number');
            $table->string('password');
            $table->string('position');
            $table->string('employee_status');
            $table->string('team');
            // $table->string('branch');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('SET NULL');
            $table->string('joining_date');
            $table->bigInteger('salary');
            $table->string('gender');
            $table->string('dob');
            $table->string('address');
            // $table->string('street');
            // $table->string('city');
            // $table->string('state');
            // $table->bigInteger('pin_code');
            $table->string('emergency_name');
            $table->bigInteger('emergency_number');
            $table->bigInteger('aadhar_card');
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('SET NULL');
            $table->string('dob');
            $table->string('age');
            $table->string('qualification');
            $table->string('certificate');
            $table->string('company');
            $table->string('experience');
            $table->string('role');
            $table->string('old_salary');
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
        Schema::dropIfExists('employees');
    }
}