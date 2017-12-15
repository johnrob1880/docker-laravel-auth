<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('paypal_invoice_id')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('kit_registration_id')->unsigned();
            $table->string('title');            
            $table->double('test_price', 2);
            $table->double('analysis_cost', 2);
            $table->double('total', 2);
            $table->string('payment_status')->nullable();
            $table->string('recurring_id')->nullable();
            $table->string('error_msg')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('kit_registration_id')->references('id')->on('kit_registrations');
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
