<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cfdi extends Model
{
  protected $table = 'c_cfdi';
  protected $primaryKey = 'cfdi_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'cfdi_id');
  }
}
