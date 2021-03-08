<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\Enums\HttpStatusCode;
use Exception;

class PermissionService
{
  public function savePermission(PermissionRequest $request)
  {
    try {
      $permission = new Permission();
      $permission->name = $request->input('name');
      $permission->active = $request->input('active');
      $permission->save();

      return response()->json(
        [
          'ok' => true,
          'permission' => new PermissionResource($permission),
          'message' => 'Permiso guardado con éxito',
          'statusCode' => HttpStatusCode::CREATED,
        ],
        HttpStatusCode::CREATED
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al guardar el permiso',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getPermission(Permission $permission)
  {
    try {
      return response()->json(
        [
          'ok' => true,
          'permission' => new PermissionResource($permission),
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener el permiso',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getPermissions()
  {
    try {
      return response()->json(
        [
          'ok' => true,
          'permissions' => new PermissionCollection(Permission::all()),
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener los permisos',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function updatePermission(PermissionRequest $request, Permission $permission)
  {
    try {
      $permission->update([
        'name' => $request->input('name'),
        'active' => $request->input('active'),
      ]);

      return response()->json(
        [
          'ok' => true,
          'permission' => new PermissionResource($permission),
          'message' => 'Permiso actualizado con éxito',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al actualizar el permiso',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function deletePermission(Permission $permission)
  {
    try {
      $permission->roles()->detach();
      $permission->delete();
      return response()->json(
        [
          'ok' => true,
          'message' => 'Permiso eliminado con éxito.',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'No se pudo eliminar el permiso',
          'error' => $e->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
