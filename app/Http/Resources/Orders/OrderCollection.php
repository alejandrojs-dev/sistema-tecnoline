<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $orders = [];

    foreach ($this->resource as $order) {
      $orders[] = [
        'id' => $order->order_id,
        'perfileria' => $order->perfileria,
        'cut' => $order->cut,
        'assembling' => $order->assembling,
        'leveling' => $order->leveling,
        'packaging' => $order->packaging,
        'shipment' => $order->shipment,
      ];
    }
    return $orders;
  }
}
