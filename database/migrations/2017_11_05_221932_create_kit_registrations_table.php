<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kit_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('origin');
            $table->string('barcode');
            $table->string('test_name');
            $table->string('test_id');
            $table->string('upgraded_from_test_id')->nullable();
            $table->decimal('test_price', 10, 2);
            $table->decimal('analysis_cost', 10, 2);
            $table->boolean('is_complete');
            $table->timestamps();

            $table->unique(['origin', 'barcode']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kit_registrations');
    }
}
