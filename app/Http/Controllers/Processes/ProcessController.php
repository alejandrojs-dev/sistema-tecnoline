<?php

namespace App\Http\Controllers\Processes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Processes\ProcessService;

class ProcessController extends Controller
{
  protected $process_service;

  public function __construct(ProcessService $process_service)
  {
    $this->process_service = $process_service;
  }

  public function itemsCountByProcess(Request $request)
  {
    if ($request->ajax()) {
      return $this->process_service->getItemsCountByProcess($request->process_id, $request->alias);
    }
  }

  public function itemsByOrder(Request $request)
  {
    if ($request->ajax()) {
      return $this->process_service->getItemsByOrder($request->order_id, $request->process_id);
    }
  }

  public function sentItemToNextProcess(Request $request, $item_id)
  {
    if ($request->ajax()) {
      return $this->process_service->sentItemToNextProcess($item_id, $request->next_process_id);
    }
  }
}
