<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Dashboard extends Model
{
  protected $table = 'e_dashboards';
  protected $primaryKey = 'dashboard_id';
  protected $fillable = ['name', 'description', 'is_slide', 'is_row_data', 'is_in_carousel', 'active'];

  //Relationships
  public function departments()
  {
    return $this->belongsToMany(Department::class, 'd_dashboard_department', 'dashboard_id', 'department_id');
  }

  //Accessors
  public function getDashboardDepartmentsAttribute()
  {
    return $this->departments()
      ->get()
      ->map->only('id_department', 'name');
  }
}
