<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class DeliveryType extends Model
{
  protected $table = 'c_delivery_types';
  protected $primaryKey = 'delivery_type_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'delivery_type_id');
  }
}
