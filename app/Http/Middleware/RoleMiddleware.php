<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Split roles by comma to support multiple roles
        $allowedRoles = array_map('trim', explode(',', $roles));

        if (!$request->user()->role || !in_array($request->user()->role->name, $allowedRoles)) {
            return response()->json([
                'error' => 'Unauthorized. Required role(s): ' . implode(' or ', $allowedRoles)
            ], 403);
        }

        return $next($request);
    }
}
