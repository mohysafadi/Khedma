<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBan
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

        $activeBan = $user->bans()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($activeBan) {
            return response()->json([
                'message' => 'تم حظر حسابك. السبب: ' . ($activeBan->reason ?? 'غير محدد')
            ], 403);
        }

        return $next($request);
    }
}
