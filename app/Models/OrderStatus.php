<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderStatus extends Model
{
  protected $table = 'c_order_status';
  protected $primaryKey = 'order_status_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'order_status_id');
  }
}
