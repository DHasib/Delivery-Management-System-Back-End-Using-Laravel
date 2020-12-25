<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class IsAdmin
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->role ==1)
        {
                return $next($request);
        }
        return $this->errorResponse('Unaunthorize.. You Can not Access This Route ', 401);
    }
}
