<?php

use Demon\AdminLaravel\Html;

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

/**4
 * 运维后台快速生成HTML片段
 * @return array|Html|object|string
 * @copyright 魔网天创信息科技
 * @author    ComingDemon
 */
function adminHtml()
{
    $html = Html::instance();
    //  获取参数
    $parm = func_get_args();
    //  如果有参数
    if (isset($parm[0])) {
        switch ($parm[0]) {
            case 'entity':
                return $html->entity($parm[1] ?? '');
                break;
            case 'card':
                return $html->card($parm[1] ?? '', $parm[2] ?? '');
                break;
            case 'button':
                return $html->button($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'a', $parm[4] ?? 'btn-xs btn-primary');
                break;
            case 'input':
                return $html->input($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'text', $parm[4] ?? '');
                break;
            case 'batch':
                return $html->batch($parm[1] ?? [], $parm[3] ?? 'i-checks');
                break;
            case 'fast':
                return $html->fast($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'span', $parm[4] ?? '');
                break;
            case 'image':
                return $html->image($parm[1] ?? '', $parm[2] ?? 50, $parm[3] ?? 50, $parm[4] ?? '');
                break;
            case 'search':
                return $html->search($parm[1] ?? null, $parm[2] ?? '', $parm[3] ?? []);
                break;
            default:
                return $html;
        }
    }

    return $html;
}
