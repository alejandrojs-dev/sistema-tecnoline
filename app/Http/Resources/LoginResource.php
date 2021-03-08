<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'hasPermissionTickets' => $this->has_permission_tickets,
      'isLogged' => $this->is_logged,
      'createdAt' => $this->created,
      'updatedAt' => $this->updated,
      'modules' => $this->user_modules,
      'department' => $this->detail->user_department,
      'ticketGroups' => $this->user_ticket_groups,
    ];
  }
}
