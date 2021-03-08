<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $modules = [];

    foreach ($this->resource as $module) {
      $modules[] = [
        'id' => $module->id,
        'name' => $module->name,
        'slug' => $module->slug,
        'path' => $module->path,
        'icon' => $module->icon,
        'order' => $module->order,
        'active' => $module->active,
        'parent_id' => $module->parent_id,
        'created_at' => $module->created,
        'updated_at' => $module->updated,
      ];
    }
    return $modules;
  }
}
