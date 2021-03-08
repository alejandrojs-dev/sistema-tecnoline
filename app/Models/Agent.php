<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Agent extends Model
{
  protected $table = 'c_agents';
  protected $primaryKey = 'agent_id';

  //Relationships
  public function orders()
  {
    return $this->hasMany(Order::class, 'agent_id');
  }
}
