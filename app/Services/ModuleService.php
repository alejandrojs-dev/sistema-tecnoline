<?php

namespace App\Services;

use App\Models\Module;
use App\Http\Resources\ModuleCollection;
use App\Http\Resources\ModuleResource;
use App\Enums\HttpStatusCode;
use App\Interfaces\IBaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class ModuleService implements IBaseService
{
  public function all()
  {
    try {
      $modules = Module::all();
      $moduleCollection = new ModuleCollection($modules);

      return response()->json(
        [
          'ok' => true,
          'modules' => $moduleCollection,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener los módulos',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function save($request)
  {
    try {
      $objModule = new Module();
      $objModule->name = $request->name;
      $objModule->slug = $request->slug;
      $objModule->path = $request->path;
      $objModule->icon = $request->icon;
      $objModule->order = $request->order;
      $objModule->active = $request->active;
      $objModule->parent_id = $request->parentId;
      $objModule->save();

      $module = new ModuleResource($objModule);

      return response()->json(
        [
          'ok' => true,
          'module' => $module,
          'message' => 'Módulo guardado con éxito',
          'statusCode' => HttpStatusCode::CREATED,
        ],
        HttpStatusCode::CREATED
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al guardar el módulo',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function get($module)
  {
    try {
      $module = new ModuleResource($module);
      return response()->json(
        [
          'ok' => true,
          'module' => $module,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener el usuario',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update($request, $module)
  {
    try {
      $module->update([
        'name' => $request->name,
        'slug' => $request->slug,
        'path' => $request->path,
        'icon' => $request->icon,
        'order' => $request->order,
        'active' => $request->active,
        'parent_id' => $request->parentId,
      ]);

      $module = new ModuleResource($module);

      return response()->json(
        [
          'ok' => true,
          'module' => $module,
          'message' => 'Módulo actualizado con éxito',
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al actualizar el módulo',
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function delete($module)
  {
    try {
      $mysql_response = DB::select(DB::raw('CALL sp_delete_modules_submodules(?)'), [$module->id]);
      $deleted = $mysql_response[0]->deleted;
      $message = $mysql_response[0]->message;
      $statusCode = $mysql_response[0]->statusCode;
      return response()->json(
        [
          'ok' => $deleted,
          'message' => $message,
          'statusCode' => $statusCode,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'No se pudo eliminar el módulo',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
