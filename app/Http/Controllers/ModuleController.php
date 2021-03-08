<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\http\Requests\ModuleRequest;
use App\Services\ModuleService;

class ModuleController extends Controller
{
  protected $module_service;

  public function __construct(ModuleService $module_service)
  {
    $this->module_service = $module_service;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->module_service->all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ModuleRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ModuleRequest $request)
  {
    if ($request->ajax()) {
      return $this->module_service->save($request);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Module  $module
   * @return \Illuminate\Http\Response
   */
  public function show(Module $module)
  {
    return $this->module_service->get($module);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ModuleRequest  $request
   * @param  \App\Models\Module  $module
   * @return \Illuminate\Http\Response
   */
  public function update(ModuleRequest $request, Module $module)
  {
    if ($request->ajax()) {
      return $this->module_service->update($request, $module);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Module  $module
   * @return \Illuminate\Http\Response
   */
  public function destroy(Module $module)
  {
    return $this->module_service->delete($module);
  }
}
