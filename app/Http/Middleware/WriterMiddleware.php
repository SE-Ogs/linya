<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WriterMiddleware
{
    /**
     * Handle an incoming request and allow users with role 'writer' or 'admin'.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();
        if (!in_array($user->role, ['writer', 'admin'])) {
            abort(403, 'Access denied. Writer privileges required.');
        }
        if ($user->status == 'Banned') {
            abort(403, 'Your account is banned.');
        }

        return $next($request);
    }
}


