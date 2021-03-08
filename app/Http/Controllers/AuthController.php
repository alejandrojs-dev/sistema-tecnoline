<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  protected $auth_service;

  /**
   * Constructor del controlador
   */
  public function __construct(AuthService $auth_service)
  {
    $this->auth_service = $auth_service;
  }

  /**
   * Método para loguear al usuario dentro del sistema
   */
  public function login(LoginRequest $request)
  {
    //if ($request->ajax()) {
    $credentials = $request->only(['username', 'password']);
    return $this->auth_service->login($credentials);
    //}
  }

  /**
   * Método para desloguear al usuario del sistema
   */
  public function logout(Request $request)
  {
    if ($request->ajax()) {
      return $this->auth_service->logout();
    }
  }

  public function getToken(Request $request)
  {
    if ($request->ajax()) {
      return $this->auth_service->getToken($request);
    }
  }

  public function verifyUserHasPermission(Request $request)
  {
    if ($request->ajax()) {
      $permission = $request->input('permission');
      return $this->auth_service->verifyUserHasPermission($permission);
    }
  }
}
