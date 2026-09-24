<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route middleware: role:admin,pharmacist
 *
 * Aborts with 403 if the authenticated user's role is not in the allowed
 * list. Registered as the 'role' alias in bootstrap/app.php. Does not
 * replace or modify the existing 'auth'/'guest' middleware — routes must
 * still be inside an `auth` group for $request->user() to be present.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole($roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        if (! $user->is_active) {
            abort(403, 'Your account has been deactivated. Please contact an administrator.');
        }

        return $next($request);
    }
}
