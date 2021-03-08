<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class ShippingAddress extends Model
{
  protected $table = 'c_shipping_addresses';
  protected $primaryKey = 'shipping_address_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'shipping_address_id');
  }
}
