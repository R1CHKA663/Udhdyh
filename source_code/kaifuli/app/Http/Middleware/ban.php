<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ban
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!\Auth::user()->is_ban) {
            return $next($request);
        }
        return response(['error' => 'Вы заблокированы :(']);
    }
}
