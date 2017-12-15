<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->date('date_of_birth');
            $table->boolean('results_via_email');
            $table->boolean('verified');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('locale')->default('en');
            $table->string('origin')->default('usa');
            $table->string('last_ip')->nullable();
            $table->date('date_of_last_login')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
