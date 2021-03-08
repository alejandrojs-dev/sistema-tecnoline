<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Process extends Model
{
  protected $table = 'c_production_processes';
  protected $primaryKey = 'process_id';
  public $timestamps = false;

  //Relationships
  public function order_detail()
  {
    return $this->belongsTo(OrderDetail::class, 'process_id');
  }

  public function checks()
  {
    return $this->hasMany(Check::class, 'process_id');
  }
}
