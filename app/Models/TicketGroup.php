<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketGroup extends Model
{
  protected $table = 'c_ticket_groups';
  protected $primaryKey = 'id_ticket_group';

  public function users()
  {
    return $this->belongsToMany(User::class, 'd_user_ticket_group', 'id_ticket_group', 'id_user');
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
