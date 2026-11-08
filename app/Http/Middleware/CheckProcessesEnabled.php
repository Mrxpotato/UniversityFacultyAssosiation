<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProcessesEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! SystemSetting::areProcessesEnabled()) {
            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'System processes are currently disabled. The system is in read-only mode.',
                ], 403);
            }

            return redirect()->back()->withFallback(route('dashboard'))
                ->with('error', 'System processes are currently disabled. The system is in read-only mode. You can only view existing records.');
        }

        return $next($request);
    }
}
