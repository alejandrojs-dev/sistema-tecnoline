<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderService;
use App\http\Requests\Orders\OrderRequest;

class OrderController extends Controller
{
  protected $order_service;

  public function __construct(OrderService $order_service)
  {
    $this->order_service = $order_service;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->order_service->all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\OrderRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(OrderRequest $request)
  {
    if ($request->ajax()) {
      return $this->order_service->save($request);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Order  $order
   * @return \Illuminate\Http\Response
   */
  public function show(Order $order)
  {
    return $this->order_service->get($order);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\OrderRequest  $request
   * @param  \App\Order  $order
   * @return \Illuminate\Http\Response
   */
  public function update(OrderRequest $request, Order $order)
  {
    if ($request->ajax()) {
      return $this->order_service->update($request, $order);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Order  $order
   * @return \Illuminate\Http\Response
   */
  public function destroy(Order $order)
  {
    return $this->order_service->delete($order);
  }
}
