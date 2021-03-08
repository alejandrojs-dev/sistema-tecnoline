<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Module extends Model
{
  protected $table = 'c_modules';
  protected $appends = ['permissions'];

  public function getPermissionsAttribute()
  {
    if ($this->parent_id) {
      return Permission::where('name', 'LIKE', '%' . str_replace('-', ' ', $this->slug . '%'))
        ->pluck('name')
        ->toArray();
    }
  }

  public function submodules()
  {
    return $this->hasMany(Module::class, 'parent_id');
  }

  public function parentNode()
  {
    return $this->belongsTo(Module::class, 'parent_id');
  }

  public function scopeParent($query)
  {
    return $query->where('parent_id', 0);
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
