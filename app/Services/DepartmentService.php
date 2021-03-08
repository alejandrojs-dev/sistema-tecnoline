<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Enums\HttpStatusCode;
use App\Interfaces\IBaseService;
use Exception;

class DepartmentService implements IBaseService
{
  public function all()
  {
    try {
      $departments = Department::all();
      $departmentCollection = new DepartmentCollection($departments);

      return response()->json(
        [
          'ok' => true,
          'departments' => $departmentCollection,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener los departamentos',
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
      $objDepartment = new Department();
      $objDepartment->name = $request->name;
      $objDepartment->abbreviation = $request->abbreviation;
      $objDepartment->active = $request->active;
      $objDepartment->save();

      $department = new DepartmentResource($objDepartment);

      return response()->json(
        [
          'ok' => true,
          'department' => $department,
          'message' => 'Departamento guardado con éxito',
          'statusCode' => HttpStatusCode::CREATED,
        ],
        HttpStatusCode::CREATED
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al guardar departamento',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function get($department)
  {
    try {
      $department = new DepartmentResource($department);
      return response()->json(
        [
          'ok' => true,
          'deparment' => $department,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener el departamento',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update($request, $department)
  {
    try {
      $department->update([
        'name' => $request->name,
        'abbreviation' => $request->abbreviation,
        'active' => $request->active,
      ]);

      $department = new DepartmentResource($department);

      return response()->json(
        [
          'ok' => true,
          'department' => $department,
          'message' => 'Departamento actualizado con éxito',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al actualizar el departamento',
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function delete($department)
  {
    try {
      $department->delete();
      return response()->json(
        [
          'ok' => true,
          'message' => 'Departamento eliminado con éxito.',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al eliminar el departamento',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
