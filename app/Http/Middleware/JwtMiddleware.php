<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        try {

            //PERMITIMOS EL ENVIO DEL TOKEN EN UNA VARIABLE POR SI SE ENVIA EN UN VINCULO
            if (isset($request->access_token)) {
                if(!$request->header("Authorization")){
                    $request->headers->set("Authorization", "Bearer {$request->access_token}");
                }
            }


            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return abort(401);
                //return response()->json(['status' => 'Authorization Token not found'],401);
            }
        }
        return $next($request);
    }
}