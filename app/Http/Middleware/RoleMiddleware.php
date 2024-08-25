<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Jika user tidak memiliki salah satu peran yang diperlukan, kembalikan respons 403
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}