<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Process;

class OrderDetail extends Model
{
  protected $table = 'd_orders';
  protected $primaryKey = 'order_detail_id';

  //Relations
  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }

  public function process()
  {
    return $this->hasOne(Process::class, 'process_id');
  }
}
