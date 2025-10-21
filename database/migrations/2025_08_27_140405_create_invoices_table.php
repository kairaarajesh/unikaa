<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('payment');
            $table->string('customer_id');
            $table->string('email')->nullable()->unique();
            $table->bigInteger('number');
            $table->string('place');
            $table->string('category');
            $table->string('date');
            $table->string('amount');
            $table->string('total_amount');
            $table->bigInteger('discount');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('SET NULL');
            $table->bigInteger('employee_details');
            $table->string('tax');
            $table->bigInteger('subtotal');
            $table->bigInteger('service_tax');
            $table->bigInteger('service_tax_amount');
            $table->bigInteger('service_total_calculation');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branch')->onDelete('SET NULL');
            // Stores the full list of service line items as JSON (from UI)
            $table->longText('service_items');
            $table->longText('purchase_items');
            $table->bigInteger('purchase_total_amount');
            $table->string('gender');

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
        Schema::dropIfExists('invoices');
    }
}
