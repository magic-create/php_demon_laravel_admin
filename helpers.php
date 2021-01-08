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
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function adminUrl($path = null, $parameters = [], $secure = null)
{
    return url(config('admin.web.path') . ($path ? '/' . ltrim($path, '/') : ''), $parameters, $secure);
}

/**
 * 运维后台快速生成HTML片段
 *
 * @return array|Html|object|string
 *
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
                return $html->button($parm[1] ?? '', $parm[2] ?? [], $parm[3] ?? 'a', $parm[4] ?? 'btn-sm btn-primary');
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
function adminTableButton($action = '', $type = '', $parm = [])
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
    return adminHtml()->button(($icon ? adminHtml()->fast('', [], 'i', $icon) : '') . $text, [
        'action' => $action, 'title' => $title, 'data-toggle' => 'tooltip', 'data-trigger' => 'hover'
    ], $tag, "btn-{$size} btn-{$theme} " . ($parm['class'] ?? ''));
}
