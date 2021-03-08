<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserDetail;
use App\Models\Dashboard;

class Department extends Model
{
  protected $table = 'c_departments';
  protected $primaryKey = 'id_department';
  protected $fillable = ['name', 'abbreviation', 'active'];

  //Relations
  public function users()
  {
    return $this->hasMany(UserDetail::class, 'id_department');
  }

  public function dashboards()
  {
    return $this->belongsToMany(Dashboard::class, 'd_dashboard_department', 'department_id', 'dashboard_id');
  }

  public function getCreatedAttribute()
  {
    return $this->created_at->format('Y-m-d H:i');
  }

  public function getUpdatedAttribute()
  {
    return $this->created_at->format('Y-m-d H:i');
  }
}
