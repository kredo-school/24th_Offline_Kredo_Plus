<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGenderIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */


    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->hasGender()) {
            // 元々入ろうとしていたURLを覚えておく（登録後に戻すため）
            session(['url.intended' => $request->fullUrl()]);

            return redirect()->route('dashboard')
                ->with('showGenderModal', true);
        }

        return $next($request);
    }
}
