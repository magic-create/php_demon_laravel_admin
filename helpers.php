<?php

use \Demon\Library\Admin;

/**
 * 运维后台URL路径
 *
 * @param null  $path
 * @param array $parameters
 * @param null  $secure
 *
 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function adminUrl($path = null, $parameters = [], $secure = null)
{
    return url(config('admin.web.path') . ($path ? '/' . $path : ''), $parameters, $secure);
}