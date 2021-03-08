<?php
namespace App\Services\Orders;

use App\Interfaces\IBaseService;
use App\Http\Resources\Orders\OrderCollection;
use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatusCode;
use App\Models\Order;
use Exception;

class OrderService implements IBaseService
{
  public function all()
  {
    try {
      $subquery = Order::select(
        'c_orders.order_id',
        DB::raw('CASE WHEN d_orders.process_id = 1 THEN COUNT(*) ELSE 0 END AS perfileria'),
        DB::raw('CASE WHEN d_orders.process_id = 2 THEN COUNT(*) ELSE 0 END AS cut'),
        DB::raw('CASE WHEN d_orders.process_id = 3 THEN COUNT(*) ELSE 0 END AS assembling'),
        DB::raw('CASE WHEN d_orders.process_id = 4 THEN COUNT(*) ELSE 0 END AS leveling'),
        DB::raw('CASE WHEN d_orders.process_id = 5 THEN COUNT(*) ELSE 0 END AS packaging'),
        DB::raw('CASE WHEN d_orders.process_id = 6 THEN COUNT(*) ELSE 0 END AS shipment')
      )
        ->from('c_orders')
        ->join('d_orders', function ($query) {
          $query->on('d_orders.order_id', '=', 'c_orders.order_id');
        })
        ->join('c_production_processes', function ($query) {
          $query->on('c_production_processes.process_id', '=', 'd_orders.process_id');
        })
        ->where('c_orders.order_status_id', '=', 7)
        ->groupBy('c_orders.order_id', 'd_orders.process_id');

      $orders = Order::select(
        'sub.order_id',
        DB::raw('MAX(sub.perfileria) AS perfileria'),
        DB::raw('MAX(sub.cut) AS cut'),
        DB::raw('MAX(sub.assembling) AS assembling'),
        DB::raw('MAX(sub.leveling) AS leveling'),
        DB::raw('MAX(sub.packaging) AS packaging'),
        DB::raw('MAX(sub.shipment) AS shipment')
      )
        ->from(DB::raw('(' . $subquery->toSql() . ') AS sub'))
        ->mergeBindings($subquery->getQuery())
        ->groupBy('sub.order_id')
        ->get();

      $ordersCollection = new OrderCollection($orders);

      return response()->json(
        [
          'ok' => true,
          'orders' => $ordersCollection,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al intentar recuperar los pedidos',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function get($order)
  {
  }

  public function save($request)
  {
    dd($request);
  }

  public function update($request, $order)
  {
  }

  public function delete($order)
  {
  }
}
