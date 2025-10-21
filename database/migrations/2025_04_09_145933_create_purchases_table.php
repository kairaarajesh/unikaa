<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('purchases')) {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_number');
            $table->string('Quantity');
            $table->bigInteger('price');
            $table->bigInteger('total_amount');
            $table->string('branch');
            $table->string('product_code');
            $table->string('payment');
            $table->unsignedBigInteger('management_id')->nullable();
            $table->foreign('management_id')->references('id')->on('management')->onDelete('SET NULL');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('SET NULL');
            $table->bigInteger('employee_details');
            $table->bigInteger('tax');
            $table->bigInteger('total_calculation');
            $table->bigInteger('discount');
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
        Schema::dropIfExists('purchases');
    }
}