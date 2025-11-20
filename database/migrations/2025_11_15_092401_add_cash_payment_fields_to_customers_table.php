<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashPaymentFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('cash_amount', 10, 2)->nullable()->after('payment');
            $table->decimal('cash_refund_amount', 10, 2)->nullable()->after('cash_amount');
            $table->decimal('cash_total_amount', 10, 2)->nullable()->after('cash_refund_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['cash_amount', 'cash_refund_amount', 'cash_total_amount']);
        });
    }
}
