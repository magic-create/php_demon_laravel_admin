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
 * 是否为Tabs页或者替换类型
 *
 * @param string      $type
 * @param null        $url
 * @param bool|string $replace
 *
 * @return bool
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_tabs($type = 'html', $url = null, $replace = false)
{
    $key = (string)config('admin.tabs');
    $url = $url ? : url()->full();
    if ($replace !== false)
        return str_replace("{$key}={$type}", "{$key}={$replace}", $url);

    if ($type == 'html' && (DEMON_INAJAX || DEMON_SUBMIT))
        return true;

    parse_str(parse_url($url)['query'] ?? '', $parm);

    return $key ? $type ? arguer($key, '', 'string', $parm) === $type : arguer($key, false, 'bool', $parm) : false;
}

/**
 * 运维后台输出视图
 *
 * @param string|null                                   $view
 * @param \Illuminate\Contracts\Support\Arrayable|array $data
 * @param array                                         $mergeData
 *
 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_view($view = null, $data = [], $mergeData = [])
{
    $factory = app(\Illuminate\View\Factory::class);
    if (func_num_args() === 0)
        return $factory;

    return $factory->make(mb_stripos($view, 'admin::') === false ? 'admin::' . $view : $view, $data, $mergeData);
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
 * 去掉AdminUrl前缀
 *
 * @param null $url
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_url_repre($url = null)
{
    return bomber()->strReplaceOnce(admin_url(), '', $url ? : url()->full(), 'pre');
}

/**
 * 运维后台通过错误码获取错误翻译
 *
 * @param       $key
 * @param array $replace
 * @param null  $locale
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_error($key, $replace = [], $locale = null)
{
    $name = "admin::base.error.{$key}";
    $trans = __($name, $replace, $locale);

    return $trans == $name ? "Error : {$key}" : $trans;
}

/**
 * 运维后台CDN资源
 *
 * @param string $path
 * @param bool   $force
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_cdn($path = '', $force = false)
{
    return ($force ? config('admin.cdn.url') . '/' : config('admin.assets')) . $path;
}

/**
 * 运维后台静态资源
 *
 * @param string $path
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_static($path = '')
{
    return config('admin.static') . ($path ? '/' . ltrim($path, '/') : '');
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
            case 'input':
                return $html->input($parm[1] ?? null, $parm[2] ?? '', $parm[3] ?? []);
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
 * @param string $action
 * @param string $type
 * @param array  $parm
 *
 * @return string
 *
 * @author    ComingDemon
 * @copyright 魔网天创信息科技
 */
function admin_button($action = '', $type = '', $parm = [])
{
    //  type和action相同的情况下可以向前一位参数
    if (is_array($type)) {
        $parm = $type;
        $type = $action;
    }
    $text = $parm['text'] ?? '';
    $title = $parm['title'] ?? $text;
    $modal = $parm['modal'] ?? $text;
    $tag = $parm['tag'] ?? 'button';
    $size = $parm['size'] ?? 'sm';
    $icon = $parm['icon'] ?? '';
    $auto = $parm['auto'] ?? false;
    $type = $type ? : $action;
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
        case 'detail':
        case 'content':
            $theme = $parm['theme'] ?? 'info';
            $icon = $parm['icon'] ?? 'fa fa-info';
            break;
        //  审核
        case 'audit':
        case 'check':
        case 'approve':
        case 'confirm':
            $theme = $parm['theme'] ?? 'warning';
            $icon = $parm['icon'] ?? 'fa fa-gavel';
            break;
        //  默认
        default:
            $theme = $parm['theme'] ?? 'secondary';
            if ($title === '')
                $icon = $parm['icon'] ?? 'fa fa-question';
            break;
    }

    //  返回内容
    return admin_html()->button(($icon ? admin_html()->fast('', [], 'i', $icon) : '') . $text, ($parm['attr'] ?? []) + [
            'action' => $action . ($auto ? (ucfirst(is_string($auto) ? $auto : 'auto')) : ''), 'title' => $title, 'modal' => $modal, 'data-toggle' => 'tooltip', 'data-trigger' => 'hover'
        ], $tag, "btn-{$size} btn-{$theme} " . ($parm['class'] ?? ''));
}
