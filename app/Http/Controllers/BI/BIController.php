<?php

namespace App\Http\Controllers\BI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboards\DashboardService;

class BIController extends Controller
{
  protected $dashboard_service;

  public function __construct(DashboardService $dashboard_service)
  {
    $this->dashboard_service = $dashboard_service;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function dashboardsByDep(Request $request)
  {
    if ($request->ajax()) {
      return $this->dashboard_service->getDashboardsByDep($request->department_id);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getBIData(Request $request)
  {
    return $this->dashboard_service->getBIData($request->dashboard_id, $request->data_type);
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function createVueFileComponent(Request $request)
  {
    if ($request->ajax()) {
      return $this->dashboard_service->createVueFileComponent($request->dashboardId);
    }
  }
}
