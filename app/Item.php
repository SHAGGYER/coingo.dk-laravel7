<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function place() {
      return $this->belongsTo('App\Place');
    }

    public function batches() {
      return $this->hasMany('App\Batch');
    }

    public function supplier() {
      return $this->belongsTo('App\Supplier');
    }
}
