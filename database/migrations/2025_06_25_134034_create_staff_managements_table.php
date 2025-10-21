<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_managements', function (Blueprint $table) {
            $table->id();
            $table->string('trainer');
            $table->string('subject');
            $table->string('salary');
            $table->string('trainer_email');
            $table->bigInteger('trainer_number');
            $table->string('branch');
            $table->string('joining_date');
            $table->string('gender');
            $table->string('dob');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->bigInteger('pin_code');
            $table->string('emergency_name');
            $table->bigInteger('emergency_number');
            $table->bigInteger('aadhar_card');
            $table->string('commission');
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
        Schema::dropIfExists('staff_managements');
    }
}