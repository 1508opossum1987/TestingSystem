<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActive
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Ваш аккаунт заблокирован. Обратитесь к администратору.');
        }

        return $next($request);
    }
}
