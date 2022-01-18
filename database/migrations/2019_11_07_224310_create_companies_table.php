<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('cvr')->nullable();
            $table->integer('reg_nr')->nullable();
            $table->bigInteger('account_number')->nullable();
            $table->integer('invoice_number')->nullable()->default(1);

            $table->string('address');
            $table->string('city');
            $table->string('zip');
            $table->string('country');
            $table->string('phone');
            $table->string('email');

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
        Schema::dropIfExists('companies');
    }
}
