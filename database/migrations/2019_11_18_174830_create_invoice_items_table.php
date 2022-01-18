<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('invoice_id');
          $table->string('unit');
          $table->string('comments')->nullable();
          $table->string('account')->nullable();
          $table->integer('quantity')->nullable();
          $table->float('price')->nullable();
          $table->float('discount')->nullable();
          $table->string('freetext')->nullable();
          $table->integer('item_id')->nullable();
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
        Schema::dropIfExists('invoice_items');
    }
}
