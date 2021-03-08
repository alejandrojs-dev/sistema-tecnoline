<?php
namespace App\Services\Processes;

use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatusCode;
use App\Models\Check;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;

class ProcessService
{
  public function getItemsCountByProcess($process_id, $alias)
  {
    try {
      $subquery = Order::select(
        'c_orders.order_id',
        DB::raw('CASE WHEN d_orders.process_id =' . $process_id . ' THEN COUNT(*) ELSE 0 END AS ' . $alias)
      )
        ->from('c_orders')
        ->join('d_orders', function ($join) {
          $join->on('d_orders.order_id', '=', 'c_orders.order_id');
        })
        ->join('c_production_processes', function ($join) {
          $join->on('c_production_processes.process_id', '=', 'd_orders.process_id');
        })
        ->where('c_orders.order_status_id', '=', 7)
        ->groupBy('c_orders.order_id', 'd_orders.process_id');

      $orders = Order::select('sub.order_id AS id', DB::raw('MAX(sub.' . $alias . ') AS ' . $alias))
        ->from(DB::raw('(' . $subquery->toSql() . ') AS sub'))
        ->mergeBindings($subquery->getQuery())
        ->groupBy('sub.order_id')
        ->get();

      return response()->json(
        [
          'ok' => true,
          'orders' => $orders,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al intentar recuperar la data',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $e->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getItemsByOrder($order_id, $process_id)
  {
    try {
      $order = Order::select(
        'c_orders.order_id AS id',
        'c_clients.client_name AS client',
        'c_factories.factory',
        'c_orders.created_at AS createdAt'
      )
        ->from('c_orders')
        ->join('c_clients', function ($join) {
          $join->on('c_clients.client_id', '=', 'c_orders.client_id');
        })
        ->join('c_factories', function ($join) {
          $join->on('c_factories.factory_id', '=', 'c_orders.factory_id');
        })
        ->where('c_orders.order_id', '=', $order_id)
        ->first();

      $order_items = OrderDetail::select(
        'd_orders.order_detail_id AS id',
        'd_orders.item_id AS itemNumber',
        'c_products.product',
        'c_articles.article',
        'c_production_processes.next_process_id AS nextProcessId'
      )
        ->from('d_orders')
        ->join('c_products', function ($join) {
          $join->on('c_products.product_id', '=', 'd_orders.product_id');
        })
        ->join('c_articles', function ($join) {
          $join->on('c_articles.article_id', '=', 'd_orders.article_id');
        })
        ->join('c_production_processes', function ($join) {
          $join->on('c_production_processes.process_id', '=', 'd_orders.process_id');
        })
        ->where('d_orders.order_id', '=', $order_id)
        ->where('d_orders.process_id', '=', $process_id)
        ->get();

      $order_checks = Check::select(
        'c_checks.check_id AS id',
        'c_checks.check_description AS description',
        'c_checks.is_required AS isRequired'
      )
        ->from('c_checks')
        ->where('c_checks.process_id', '=', $process_id)
        ->get();

      foreach ($order_checks as $order_check) {
        $order_check->isRequired = boolval($order_check->isRequired);
        $order_check['isChecked'] = false;
        $order_check['isInvalid'] = false;
      }

      foreach ($order_items as $order_item) {
        $order_item['checks'] = $order_checks;
      }

      $order_data = [
        'id' => $order->id,
        'client' => $order->client,
        'factory' => $order->factory,
        'createdAt' => $order->createdAt,
        'items' => $order_items,
      ];

      return response()->json([
        'ok' => true,
        'message' => 'Data recuperada con éxito',
        'statusCode' => HttpStatusCode::OK,
        'order' => $order_data,
        'checks' => $order_checks,
      ]);
    } catch (Exception $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al intentar recuperar la data',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $e->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function sentItemToNextProcess($item_id, $next_process_id)
  {
    try {
      $item = OrderDetail::find($item_id);

      $item->process_id = $next_process_id;

      $item->update();

      return response()->json([
        'ok' => true,
        'message' => 'Partida enviada a siguiente proceso con éxito',
        'statusCode' => HttpStatusCode::OK,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'ok' => false,
        'message' => 'Error al cambiar de proceso la partida',
        'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        'error' => $e->getMessage(),
      ]);
    }
  }
}
