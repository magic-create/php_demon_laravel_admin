<?php

use Demon\AdminLaravel\Html;

/**
 * 运维后台代码路径
 *
 * @param string $path
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_path($path = '')
{
    return app_path('Admin' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

/**
 * 运维后台URL路径
 *
 * @param null  $path
 * @param array $parameters
 * @param null  $secure
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_url($path = null, $parameters = [], $secure = null)
{
    $url = url(config('admin.path') . ($path ? '/' . ltrim($path, '/') : ''), [], $secure);

    return $url . ($parameters ? ((strpos($url, '?') !== false) ? '&' : '?') . http_build_query($parameters) : '');
}

/**
 * 运维后台快速生成HTML片段
 *
 * @return array|Html|object|string
 *
 * @copyright 魔网天创信息科技
 * @author    ComingDemon
 */
function admin_html()
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
            case 'button':
                return $html->button($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'a', $parm[4] ?? 'btn-sm btn-primary');
                break;
            case 'switch':
                return $html->switch($parm[1] ?? [], $parm[2] ?? [], $parm[3] ?? 'switch hidden');
                break;
            case 'fast':
                return $html->fast($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'span', $parm[4] ?? '');
                break;
            case 'image':
                return $html->image($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? '30px', $parm[4] ?? '');
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

/**
 * 运维后台快速生成Table操作按钮
 *
 * @param       $title
 * @param null  $action
 * @param array $parm
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_button($action = '', $type = '', $parm = [])
{
    $text = $parm['text'] ?? '';
    $title = $parm['title'] ?? $text;
    $tag = $parm['tag'] ?? 'button';
    $size = $parm['size'] ?? 'sm';
    $theme = $parm['theme'] ?? 'secondary';
    $icon = $parm['icon'] ?? '';
    $action = $action ?? $type;
    switch ($type) {
        //  新增
        case 'add':
        case 'new':
        case 'create':
        case 'insert':
            $theme = $parm['theme'] ?? 'success';
            $icon = $parm['icon'] ?? 'fa fa-plus';
            break;
        //  删除
        case 'del':
        case 'leave':
        case 'remove':
        case 'delete':
            $theme = $parm['theme'] ?? 'danger';
            $icon = $parm['icon'] ?? 'fa fa-trash';
            break;
        //  修改
        case 'edit':
        case 'modify':
        case 'change':
        case 'update':
            $theme = $parm['theme'] ?? 'primary';
            $icon = $parm['icon'] ?? 'fa fa-edit';
            break;
        //  查询
        case 'get':
        case 'info':
        case 'search':
        case 'select':
            $theme = $parm['theme'] ?? 'info';
            $icon = $parm['icon'] ?? 'fa fa-info';
            break;
        //  默认
        default:
            $theme = $parm['theme'] ?? 'secondary';
            if (!$title)
                $icon = $parm['icon'] ?? 'fa fa-question';
            break;
    }

    //  返回内容
    return admin_html()->button(($icon ? admin_html()->fast('', [], 'i', $icon) : '') . $text, [
        'action' => $action, 'title' => $title, 'data-toggle' => 'tooltip', 'data-trigger' => 'hover'
    ], $tag, "btn-{$size} btn-{$theme} " . ($parm['class'] ?? ''));
}
