<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $table = 'c_clients';
  protected $primaryKey = 'client_id';

  //Relationships
  public function orders()
  {
    $this->hasMany(Order::class, 'client_id');
  }
}
