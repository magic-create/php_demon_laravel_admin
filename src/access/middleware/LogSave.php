<?php

namespace Demon\AdminLaravel\access\middleware;

use Closure;
use Illuminate\Http\Request;

class LogSave
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
        //  Response
        $response = $next($request);
        //  Log
        if (config('admin.access') && config('admin.log'))
            app()->call([app()->make(config('admin.log')), 'saveLog'], ['response' => $response]);

        //  Return
        return $response;
    }
}
