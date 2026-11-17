<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and verify user role & account status.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please sign in to access this area.');
        }

        if ($user->status === 'banned') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended by an administrator.',
            ]);
        }

        $userRole = $user->role?->name ?? match ((int) $user->role_id) {
            1 => 'admin',
            2 => 'author',
            default => 'user',
        };

        // Admin has universal access or check if user's role is in allowed roles
        if (in_array($userRole, $roles, true) || $userRole === 'admin') {
            return $next($request);
        }

        return redirect($user->dashboardRoute())
            ->with('error', 'You do not have permission to access that section.');
    }
}
