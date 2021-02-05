<?php

namespace Demon\AdminLaravel\access\middleware;

use Closure;
use Illuminate\Http\Request;

class SessionPost
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
        //  Access
        if (config('admin.access')) {
            //  Uid
            $uid = session('uid') ? : 0;
            if ($uid)
                app('admin')->setUid($uid);
            //  Attribute
            $request->attributes->add(['uid' => $uid]);
        }

        //  Next
        return $next($request);
    }
}
