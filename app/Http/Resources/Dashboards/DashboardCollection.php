<?php

namespace App\Http\Resources\Dashboards;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DashboardCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $dashboards = [];

    foreach ($this->resource as $dashboard) {
      $dashboards[] = [
        'id' => $dashboard->dashboard_id,
        'name' => $dashboard->name,
        'description' => $dashboard->description,
        'isSlide' => $dashboard->is_slide,
        'isRowData' => $dashboard->is_row_data,
        'isInCarousel' => $dashboard->is_in_carousel,
        'active' => $dashboard->active,
        'componentName' => $dashboard->component_name,
        'departments' => $dashboard->dashboard_departments,
      ];
    }
    return $dashboards;
  }
}
