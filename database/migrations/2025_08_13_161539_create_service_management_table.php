<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_management', function (Blueprint $table) {
            $table->id();
            $table->string("service_name");
            // $table->string("service_combo");
            $table->string("amount");
            $table->string("quantity");
            $table->string("tax");
            $table->string("gender");
            $table->string("total_amount");
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
        Schema::dropIfExists('service_management');
    }
}