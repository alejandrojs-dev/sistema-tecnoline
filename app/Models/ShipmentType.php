<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class ShipmentType extends Model
{
  protected $table = 'c_shipments_types';
  protected $primaryKey = 'shipment_type_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'shipment_type_id');
  }
}
