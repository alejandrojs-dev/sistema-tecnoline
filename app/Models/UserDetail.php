<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;

class UserDetail extends Model
{
  protected $table = 'd_user_detail';
  protected $hidden = ['created_at', 'updated_at', 'id_user', 'id_department', 'id_ticket_group'];
  protected $appends = ['created', 'updated'];

  public function user()
  {
    return $this->belongsTo(User::class, 'id_user');
  }

  public function department()
  {
    return $this->belongsTo(Department::class, 'id_department');
  }

  //Accesors
  public function getUserDepartmentAttribute()
  {
    return $this->department()
      ->select('id_department', 'name')
      ->first();
  }

  public function getUserTicketGroupAttribute()
  {
    return $this->ticketGroup()
      ->select('id_ticket_group', 'name')
      ->first();
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
