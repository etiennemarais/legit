<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * @param $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            return $next($request);
        }

        return response()->json([
            "status" => 401,
            "message" => "Invalid API Key",
        ], 401);
    }
}
