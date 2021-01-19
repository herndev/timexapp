<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JWTCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = auth('api')->userOrFail();
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(array(
                "message" => "Unauthorized access",
            ), 401);
        }
    }
}
