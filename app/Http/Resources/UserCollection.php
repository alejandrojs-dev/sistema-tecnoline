<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $users = [];

    foreach ($this->resource as $user) {
      $users[] = [
        'id' => $user->id,
        'username' => $user->username,
        'detail' => $user->detail,
        'department' => $user->detail->user_department,
        'ticketGroup' => $this->detail->user_ticket_group,
      ];
    }
    return $users;
  }
}
