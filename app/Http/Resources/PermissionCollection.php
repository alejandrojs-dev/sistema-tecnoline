<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $permissions = [];

    foreach ($this->resource as $permission) {
      $permissions[] = [
        'id' => $permission->id,
        'name' => $permission->name,
        'active' => $permission->abbreviation,
        'created_at' => $permission->created,
        'updated_at' => $permission->updated,
      ];
    }
    return $permissions;
  }
}
