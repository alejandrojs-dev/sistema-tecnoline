<?php

namespace App\Http\Resources\Dashboards;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
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
      'id' => $this->dashboard_id,
      'name' => $this->name,
      'description' => $this->description,
      'isInCarousel' => $this->is_in_carousel,
      'isSlide' => $this->is_slide,
      'isRowData' => $this->is_row_data,
      'active' => $this->active,
      'componentName' => $this->component_name,
      'departments' => $this->dashboard_departments,
    ];
  }
}
