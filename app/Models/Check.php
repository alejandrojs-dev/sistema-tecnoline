<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
  protected $table = 'c_checks';
  protected $primaryKey = 'check_id';

  //Relationships
  public function process()
  {
    return $this->belongsTo(Process::class, 'process_id');
  }
}
