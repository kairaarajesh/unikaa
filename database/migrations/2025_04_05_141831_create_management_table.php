<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    if (!Schema::hasTable('management')) {
        Schema::create('management', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_code');
            $table->string('Quantity');
            $table->string('price');
            $table->string('place');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branch')->onDelete('SET NULL');
            $table->string('date');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
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
        Schema::drop('management');
    }
}
