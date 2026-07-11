<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $restriction = $user->restrictions()
            ->where('expires_at', '>', now())
            ->first();

        if ($restriction) {
            return response()->json([
                'message' => 'تم تقييد حسابك: ' . $restriction->type
            ], 403);
        }

        return $next($request);
    }
}
