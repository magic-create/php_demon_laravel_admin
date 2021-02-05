<?php

namespace Demon\AdminLaravel\access\middleware;

use Closure;
use Illuminate\Http\Request;

class SessionPre
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
        //  AdminSession
        $config = [];
        foreach (config('admin.session') as $key => $val)
            $config['session.' . $key] = $val;
        //  SetSession
        config($config);
        //  File
        if (!is_dir(config('session.files')))
            bomber()->dirMake(config('session.files'));

        //  Next
        return $next($request);
    }
}
