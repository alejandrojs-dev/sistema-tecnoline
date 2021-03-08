<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Services\Dashboards\DashboardService;
use App\Http\Requests\Dashboards\DashboardRequest;

class DashboardController extends Controller
{
  protected $dashboard_service;

  public function __construct(DashboardService $dashboard_service)
  {
    $this->dashboard_service = $dashboard_service;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->dashboard_service->all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\DashboardRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(DashboardRequest $request)
  {
    if ($request->ajax()) {
      return $this->dashboard_service->save($request);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Dashboard  $dashboard
   * @return \Illuminate\Http\Response
   */
  public function show(Dashboard $dashboard)
  {
    return $this->dashboard_service->get($dashboard);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\DashboardRequest  $request
   * @param  \App\Models\Dashboard  $dashboard
   * @return \Illuminate\Http\Response
   */
  public function update(DashboardRequest $request, Dashboard $dashboard)
  {
    if ($request->ajax()) {
      return $this->dashboard_service->update($request, $dashboard);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Dashboard  $dashboard
   * @return \Illuminate\Http\Response
   */
  public function destroy(Dashboard $dashboard)
  {
    return $this->dashboard_service->delete($dashboard);
  }
}
