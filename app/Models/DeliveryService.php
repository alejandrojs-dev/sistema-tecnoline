<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class DeliveryService extends Model
{
  protected $table = 'c_delivery_services';
  protected $primaryKey = 'delivery_service_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'delivery_service_id');
  }
}
