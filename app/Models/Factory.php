<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Factory extends Model
{
  protected $table = 'c_factories';
  protected $primaryKey = 'factory_id';

  //Relationships
  public function orders()
  {
    return $this->hasMany(Order::class, 'factory_id');
  }
}
