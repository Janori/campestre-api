<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Helpers\JResponse;

class VerifyJWTToken{

    public static $token = null;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        try{
            $user = JWTAuth::toUser($request->header('Authorization'));
            $token = $request->header('Authorization');
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $token = null;
                return response()->json(JResponse::set(false,'token_expired', $e->getStatusCode()));
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $token = null;
                return response()->json(JResponse::set(false,'token_invalid', $e->getStatusCode()));
            }else{
                $token = null;
                return response()->json(JResponse::set(false,'token_is_required'));
            }
        }
       return $next($request);
    }
}

