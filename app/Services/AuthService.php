<?php

namespace App\Services;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\LoginResource;
use App\Enums\HttpStatusCode;

class AuthService
{
  public function login($credentials)
  {
    try {
      $token = JWTAuth::attempt($credentials);

      if (!$token) {
        return response()->json(
          [
            'ok' => false,
            'message' => 'Credenciales inválidas',
            'statusCode' => HttpStatusCode::UNAUTHORIZED,
          ],
          HttpStatusCode::UNAUTHORIZED
        );
      } else {
        $user = JWTAuth::user();

        $user->is_logged = 1;

        $user->save();

        $token_info = [
          'value' => $token,
          'isValid' => true,
        ];

        return response()->json(
          [
            'ok' => true,
            'token' => $token_info,
            'message' => 'Se ha iniciado sesión',
            'user' => new LoginResource($user),
            'statusCode' => HttpStatusCode::OK,
          ],
          HttpStatusCode::OK
        );
      }
    } catch (JWTException $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Token no generado',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function logout()
  {
    try {
      $user = JWTAuth::user();

      $user->is_logged = 0;

      $user->save();

      $token = JWTAuth::getToken();

      JWTAuth::invalidate($token);

      return response()->json(
        [
          'ok' => true,
          'message' => 'Se ha cerrado la sesión',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (JWTException $e) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al intentar desloguarte',
          'error' => $e->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getToken()
  {
    $token = JWTAuth::getToken()->get();

    $token_info = [
      'value' => $token,
      'isValid' => true,
    ];

    return response()->json(
      [
        'ok' => true,
        'token' => $token_info,
        //'isValid'       => $isValid,
        'statusCode' => HttpStatusCode::OK,
      ],
      HttpStatusCode::OK
    );
  }

  public function verifyUserHasPermission($permission)
  {
    $hasPermission = false;

    $user = JWTAuth::user();

    if ($user->hasPermissionTo($permission, 'web')) {
      $hasPermission = true;
    }

    return response()->json(
      [
        'ok' => true,
        'hasPermission' => $hasPermission,
        'statusCode' => HttpStatusCode::OK,
      ],
      HttpStatusCode::OK
    );
  }

  // public function refreshToken(Request $request)
  // {
  //     try {
  //         $token = JWTAuth::getToken();
  //         $refreshed_token = JWTAuth::refresh($token);
  //         $user = JWTAuth::setToken($refreshed_token)->authenticate();
  //         $request->headers->set('Authorization', 'Bearer '. $refreshed_token);

  //     } catch (JWTException $e) {

  //         return response()->json([
  //             'ok' => false,
  //             'message' => 'El token no se pudo refrescar',
  //             'statusCode' => 103
  //         ], 103);

  //     }
  // }
}
