<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiTestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $validToken = "MNtu4xDy2vowkjkMdCDGiBOTJVYgfU11";
        $token = $request->bearerToken();  
        if($token != $validToken){
            return response()->json([
                'status'    => '401',
                'message'   => 'anuthorized'
            ]);
        }  

        return $next($request);
    }
}

