<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\http\Requests\PermissionRequest;
use App\Services\PermissionService;

class PermissionController extends Controller
{
  protected $permission_service;

  public function __construct()
  {
    $this->permission_service = new PermissionService();
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->permission_service->getPermissions();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\PermissionRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PermissionRequest $request)
  {
    return $this->permission_service->savePermission($request);
  }

  /**
   * Display the specified resource.
   *
   * @param  Spatie\Permission\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function show(Permission $permission)
  {
    return $this->permission_service->getPermission($permission);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\PermissionRequest  $request
   * @param  Spatie\Permission\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function update(PermissionRequest $request, Permission $permission)
  {
    return $this->permission_service->updatePermission($request, $permission);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Spatie\Permission\Models\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function destroy(Permission $permission)
  {
    return $this->permission_service->deletePermission($permission);
  }
}
