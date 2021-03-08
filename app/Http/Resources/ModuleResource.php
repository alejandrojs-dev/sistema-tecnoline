<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
      'name' => $this->name,
      'slug' => $this->slug,
      'path' => $this->path,
      'icon' => $this->icon,
      'order' => $this->order,
      'active' => $this->active,
      'parent_id' => $this->parent_id,
      'created_at' => $this->created,
      'updated_at' => $this->updated,
    ];
  }
}
