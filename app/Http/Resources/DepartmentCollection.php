<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $departments = [];

    foreach ($this->resource as $department) {
      $departments[] = [
        'id' => $department->id_department,
        'name' => $department->name,
        'abbreviation' => $department->abbreviation,
        'icon' => $department->icon,
        'active' => $department->active,
      ];
    }
    return $departments;
  }
}
