<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Tymon\JWTAuth\Facades\JWTAuth;

class SessionTimeOutMiddleware
{
  protected $session;

  public function __construct(Store $session)
  {
    $this->session = $session;
  }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      //$logged_in = $request->path() != '/';
      $time = time();
      $timeout = (int)config('app.session_timeout');

      //dd($timeout);
      //dd($time);

      if(!session('last_activity_time')) {

        $this->session->put('last_activity_time', $time);

      } elseif(($time - $this->session->get('last_activity_time') > 60)) {



        $this->session->forget('last_activity_time');
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);

        return response()->json([
          'ok' => false,
          'message' => 'Sesión cerrada por inactividad.',
          'statusCode' => 401//no autenticado
        ],  401);

      } else {
        dd($time - $this->session->get('last_activity_time'));
        $this->session->put('last_activity_time', $time);
      }
      return $next($request);
    }
}
