<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficerMiddleware
{
    /**
     * Allow access for officers. Optionally restrict to specific roles.
     *
     * Usage:
     *   'officer'                               → any officer (super_admin, university_president, university_treasurer, campus_president)
     *   'officer:university_president,campus_president' → only those specific roles (super_admin always passes)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admin always passes
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // If specific roles are passed, check against those
        if (! empty($roles)) {
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        } else {
            // Default: allow any officer
            if ($user->isOfficer()) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this area.');
    }
}
