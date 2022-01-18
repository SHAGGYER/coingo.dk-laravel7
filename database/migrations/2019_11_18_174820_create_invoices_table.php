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
          $table->bigIncrements('id');
          $table->integer('company_id');
          $table->integer('contact_id')->nullable();
          $table->integer('parent_invoice_id')->nullable();
          $table->string('type');
          $table->integer('invoice_number');
          $table->string('payment_date')->nullable();
          $table->string('account')->default('withVat');
          $table->string('headline');
          $table->tinyInteger('paid')->nullable();
          $table->string('comments')->nullable();
          $table->float('total')->nullable();
          $table->float('subtotal')->nullable();
          $table->float('vat')->nullable();
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
