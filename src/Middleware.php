<?php

namespace Demon\AdminLaravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //  Next
        return $next($request);
    }
}
