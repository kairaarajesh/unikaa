<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('SET NULL');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('employee_id')->unsigned();
           $table->date('attendance_date');
            $table->time('attendance_time');
            $table->string('category');
            $table->time('leave_time')->nullable();
           $table->time('login_time');
           $table->time('logout_time');
           $table->time('login_time');
           $table->string('permission_taken');
           $table->string('permission_reason');
           $table->time('permission_from');
           $table->time('permission_to');
           $table->string('casual_leave');
           $table->string('lop');
           $table->string('half_type');
           $table->time('half_login_time');
           $table->time('half_logout_time');
            $table->boolean('type')->default(0);
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
        Schema::dropIfExists('attendances');
    }
}