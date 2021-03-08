<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Enums\HttpStatusCode;
use App\Enums\TokenStatus;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    //$token = str_replace('Bearer ', '', $request->header('Authorization'));
    try {
      $token = $request->bearerToken();

      $user = JWTAuth::parseToken($token)->authenticate(); //JWTAuth::parseToken($token)

      if (!$user) {
        return response()->json(
          [
            'ok' => false,
            'message' => 'Usuario no logueado',
            'statusCode' => HttpStatusCode::NOT_FOUND,
          ],
          HttpStatusCode::NOT_FOUND
        );
      }
    } catch (JWTException $e) {
      if ($e instanceof TokenInvalidException) {
        $token_info = [
          'value' => $token,
          'isValid' => false,
        ];

        return response()->json(
          [
            'ok' => false,
            'token' => $token_info,
            'message' => 'Token inválido',
            'statusCode' => HttpStatusCode::BAD_REQUEST,
            'tokenStatus' => TokenStatus::INVALID,
          ],
          HttpStatusCode::BAD_REQUEST
        );
      } elseif ($e instanceof TokenExpiredException) {
        $token_info = [
          'value' => $token,
          'isValid' => false,
        ];

        return response()->json(
          [
            'ok' => false,
            'token' => $token_info,
            'message' => 'Token expirado',
            'statusCode' => HttpStatusCode::FORBIDDEN,
            'tokenStatus' => TokenStatus::EXPIRED,
          ],
          HttpStatusCode::FORBIDDEN
        );
      } else {
        $token_info = [
          'value' => $token,
          'isValid' => false,
        ];

        return response()->json(
          [
            'ok' => false,
            'token' => $token_info,
            'message' => 'Token de autorización no encontrado',
            'statusCode' => HttpStatusCode::NOT_FOUND,
            'tokenStatus' => TokenStatus::NOT_FOUND,
          ],
          HttpStatusCode::NOT_FOUND
        );
      }
    }
    return $next($request);
  }
}
