<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\UserModulesTrait;
use App\Models\UserDetail;
use App\Models\TicketGroup;

class User extends Authenticatable implements JWTSubject
{
  use Notifiable, HasRoles, UserModulesTrait;

  protected $table = 'c_users';
  protected $primaryKey = 'id';
  protected $hidden = ['password'];

  //Relations
  public function detail()
  {
    return $this->hasOne(UserDetail::class, 'id');
  }

  public function ticketGroups()
  {
    return $this->belongsToMany(TicketGroup::class, 'd_user_ticket_group', 'id_user', 'id_ticket_group');
  }

  //Accesors
  public function getCreatedAttribute()
  {
    return $this->created_at->format('Y-m-d H:i');
  }

  public function getUpdatedAttribute()
  {
    return $this->created_at->format('Y-m-d H:i');
  }

  public function getUserPermissionsAttribute()
  {
    return $this->roles()
      ->first()
      ->getAllPermissions()
      ->map->only('id', 'name');
  }

  public function getUserTicketGroupsAttribute()
  {
    $ticketGroups = $this->ticketGroups()->get();
    return $ticketGroups->map->only('id_ticket_group', 'name');
  }
  //Mutators
  public function setPasswordAttribute($value)
  {
    $this->attributes['password'] = bcrypt($value);
  }

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }
}
