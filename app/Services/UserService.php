<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDetail;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Role;
use App\Enums\HttpStatusCode;
use Exception;

class UserService
{
  public function saveUser(UserRequest $request)
  {
    try {
      $user = new User();
      $user->username = $request->input('username');
      $user->password = $request->input('password');
      $user->save();

      $user_detail = new UserDetail();
      $user_detail->short_name = $request->input('shortName');
      $user_detail->full_name = $request->input('fullName');
      $user_detail->admission_date = $request->input('admissionDate');
      $user_detail->extension = $request->input('extension');
      $user_detail->email = $request->input('email');
      $user_detail->active = $request->input('active');
      $user_detail->id_user = $user->id;
      $user_detail->id_department = $request->input('idDepartment');
      $user_detail->save();

      $role_user = new Role();
      $role_user->name = $user->username;
      $role_user->active = 1;
      $role_user->save();

      $user->assignRole($role_user);
      $role_user->permissions()->attach($request->input('permissions'));

      return response()->json(
        [
          'ok' => true,
          'message' => 'Usuario guardado con éxito',
          'user' => new UserResource($user),
          'statusCode' => HttpStatusCode::CREATED,
        ],
        HttpStatusCode::CREATED
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al guardar el usuario',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getUser(User $user)
  {
    try {
      return response()->json(
        [
          'ok' => true,
          'user' => new UserResource($user),
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener el usuario',
          'error' => $e->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getUsers()
  {
    try {
      return response()->json(
        [
          'ok' => true,
          'users' => new UserCollection(User::all()),
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener los usuarios',
          'error' => $e->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function updateUser(UserRequest $request, User $user)
  {
    try {
      if ($request->ajax()) {
        $user->update([
          'username' => $request->input('username'),
          'password' => $request->input('password'),
        ]);

        $user->detail()->update([
          'short_name' => $request->input('shortName'),
          'full_name' => $request->input('fullName'),
          'admission_date' => $request->input('admissionDate'),
          'extension' => $request->input('extension'),
          'email' => $request->input('email'),
          'active' => $request->input('active'),
          'id_department' => $request->input('idDepartment'),
        ]);

        $role_user = $user->roles()->first();
        $role_user->syncPermissions([$request->input('permissions')]);

        return response()->json(
          [
            'ok' => true,
            'message' => 'Usuario actualizado con éxito',
            'user' => new UserResource($user),
            'statusCode' => HttpStatusCode::OK,
          ],
          HttpStatusCode::OK
        );
      }
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al actualizar el usuario',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function deleteUser(User $user)
  {
    try {
      $user_role = $user->roles()->first();
      $user_permissions = $user_role
        ->getAllPermissions()
        ->pluck('id')
        ->toArray();
      $user_role->permissions()->detach($user_permissions);
      $user_role->forgetCachedPermissions();
      $user_role->delete();
      $user->delete();
      return response()->json(
        [
          'ok' => true,
          'message' => 'Usuario eliminado con éxito',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al eliminar el usuario',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
