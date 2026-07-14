<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGender
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredGender): Response
    {
        $user = $request->user();

        if (!$user->hasGender()) {
            return redirect()->route('shower.entry');
        }

        if ($user->gender !== $requiredGender) {
            abort(403, 'このページにはアクセスできません。');
        }
        return $next($request);
    }
}
