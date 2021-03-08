<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
      'id' => $this->id_department,
      'name' => $this->name,
      'abbreviation' => $this->abbreviation,
      'icon' => $this->icon,
      'active' => $this->active,
      'created_at' => $this->created,
      'updated_at' => $this->updated,
    ];
  }
}
