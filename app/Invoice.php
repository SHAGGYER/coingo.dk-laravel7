<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function items() {
      return $this->hasMany('App\InvoiceItem', 'invoice_id');
    }

    public function contact() {
      return $this->belongsTo('App\Contact');
    }

    public function parent() {
      return $this->belongsTo('App\Invoice', 'parent_invoice_id');
    }
}
