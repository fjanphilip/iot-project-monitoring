<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IoTTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-IOT-TOKEN');

        if ($token !== config('iot.token')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
