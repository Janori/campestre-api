<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class CorsMiddleware
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: X-Accept-Charset,Application, enctype,X-Accept,X-Requested-With,X-CSRF-TOKEN, X-XSRF-TOKEN,content-type,Content-Type,Authorization,Accept,Origin,Access-Control-Request-Method,Access-Control-Request-Headers');
        /*if ($request->getMethod() === "OPTIONS") {
            return $next($request);
        }*/
        return $next($request);
    }

    /*public function terminate($request, $response)
    {
        return $response->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, token');
    }*/


}