<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // BELUM LOGIN
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ROLE TIDAK SESUAI
        if (Auth::user()->role !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}