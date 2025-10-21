<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('customer_id');
            $table->string('email')->nullable()->unique();
            $table->bigInteger('number');
            $table->string('place');
            $table->string('payment');
            $table->string('category');
            $table->string('date');
            $table->bigInteger('amount');
            $table->bigInteger('total_amount');
            $table->bigInteger('discount');
            $table->string('referral_name');
            $table->string('referral_number');
            $table->string('referral_email');
             $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('SET NULL');
            $table->bigInteger('employee_details');    
           $table->bigInteger('tax');
            $table->bigInteger('subtotal');
            $table->bigInteger('service_tax');
            $table->bigInteger('service_tax_amount');
            $table->bigInteger('service_total_calculation');
            // $table->string('branch');     
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branch')->onDelete('SET NULL');
            $table->longText('service_items');
            $table->longText('purchase_items');
            $table->bigInteger('purchase_total_amount');
            $table->string('gender');
            $table->string('membership_card');
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
        Schema::dropIfExists('customers');
    }
}