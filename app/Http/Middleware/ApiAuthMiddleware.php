<?php

namespace App\Http\Middleware;
use App\Helpers\JwtAuth;

use Closure;

class ApiAuthMiddleware
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
        $token = session()->has('remember_token') ? session('remember_token') : null;
        if($token)
        {
            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($token);
            
            if($checkToken){
                return $next($request);
            }
            else{
                $data = array(
                    "status" => "error",
                    "code" => "400",
                    "message" => "Does not authenticated..."
                );
    
            }
        }
        else
        {
            $data = array(
                "status" => "error",
                "code" => "400",
                "message" => "Does not authenticated..."
            );
        }

        return response()->json($data, $data['code']);
    }
}
