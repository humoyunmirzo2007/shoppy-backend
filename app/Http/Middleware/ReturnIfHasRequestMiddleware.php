<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ReturnIfHasRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->all()) {
            return response()->json([
                'message' => 'Request yuborish mumkin emas',
            ], 400);
        }

        return $next($request);
    }
}
