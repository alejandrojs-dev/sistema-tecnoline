<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class PayMethod extends Model
{
  protected $table = 'c_pay_methods';
  protected $primaryKey = 'pay_method_id';

  //Relationships
  public function order()
  {
    return $this->belongsTo(Order::class, 'pay_method_id');
  }
}
