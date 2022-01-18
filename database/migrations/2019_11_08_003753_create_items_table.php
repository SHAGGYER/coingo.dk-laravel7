<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('company_id');
          $table->string('title');
          $table->integer('place_id')->nullable();
          $table->integer('supplier_id')->nullable();
          $table->float('price')->nullable();
          $table->string('unit')->nullable();
          $table->integer('quantity')->nullable();
          $table->string('code')->nullable();
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
        Schema::dropIfExists('items');
    }
}
