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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        
        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($this->userHasRole($user, $role)) {
                return $next($request);
            }
        }

        // If no roles match, deny access
        abort(403, 'Access denied. Insufficient permissions.');
    }

    /**
     * Check if user has a specific role
     */
    private function userHasRole($user, string $role): bool
    {
        return match($role) {
            'system_admin' => $user->isSystemAdmin(),
            'club_admin' => $user->isClubAdmin(),
            'club_manager' => $user->isClubManager(),
            'club_medical' => $user->isClubMedical(),
            'association_admin' => $user->isAssociationAdmin(),
            'association_registrar' => $user->isAssociationRegistrar(),
            'association_medical' => $user->isAssociationMedical(),
            default => false
        };
    }
} 