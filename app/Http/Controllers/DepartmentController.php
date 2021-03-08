<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\http\Requests\DepartmentRequest;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
  protected $department_service;

  public function __construct(DepartmentService $department_service)
  {
    $this->department_service = $department_service;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->department_service->all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\DepartmentRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(DepartmentRequest $request)
  {
    if ($request->ajax()) {
      return $this->department_service->save($request);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function show(Department $department)
  {
    return $this->department_service->get($department);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function update(DepartmentRequest $request, Department $department)
  {
    if ($request->ajax()) {
      return $this->department_service->update($request, $department);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function destroy(Department $department)
  {
    return $this->department_service->delete($department);
  }
}
