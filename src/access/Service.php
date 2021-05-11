<?php

namespace Demon\AdminLaravel\access;

use Demon\AdminLaravel\access\model\AllotModel;
use Demon\AdminLaravel\access\model\MenuModel;
use Demon\AdminLaravel\access\model\RoleModel;
use Demon\AdminLaravel\access\model\UserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class Service
{
    use Macroable;

    /**
     * @var array
     */
    public $userMenu = [];

    /**
     * @var int
     */
    public $uid = 0;

    /**
     * @var array
     */
    public $badge = [];

    /**
     * @var array
     */
    public $breadcrumb = [];

    /**
     * @var array
     */
    public $notification = [];

    /**
     * @var string
     */
    public $pathPre = '';

    /**
     * 获取翻译结果
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
    public function getLang($key, $replace = [], $locale = null)
    {
        $name = "admin::base.access.{$key}";
        $trans = __($name, $replace, $locale);

        return $trans == $name ? $key : $trans;
    }

    /**
     * 自动识别__开头的字符串获取翻译
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
    public function autoLang($key, $replace = [], $locale = null)
    {
        if (mb_stripos($key, '__') === 0) {
            $name = 'admin::' . mb_substr($key, 2);
            $trans = __($name, $replace, $locale);

            return $trans == $name ? mb_substr($key, 2) : $trans;
        }

        return $key;
    }

    /**
     * 获取用户的全部角色
     *
     * @param $uid
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserRids(int $uid):array
    {
        return AllotModel::where('uid', $uid)->get('rid')->pluck('rid')->toArray();
    }

    /**
     * 获取角色列表
     *
     * @param bool $filterStatus
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getRoleList($filterStatus = false)
    {
        $self = RoleModel::orderBy('system', 'desc');
        if ($filterStatus)
            $self->where('status', 1);
        $list = $self->get();
        foreach ($list as $item)
            $item['deepName'] = self::autoLang($item['name']);

        return $list;
    }

    /**
     * 获取指定角色的全部菜单IDs
     *
     * @param array $rids
     *
     * @return array //mids
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getRoleMids(int $rids):array
    {
        $mids = [];
        $list = RoleModel::where('rid', $rids)->orderBy('rid', 'desc')->get('mids')->pluck('mids')->toArray();
        $all = MenuModel::where('status', 1)->get('mid')->pluck('mid')->toArray();
        foreach ($list as $key => $val) {
            if ($val == '*') {
                $mids = array_merge($mids, $all);
                break;
            }
            else if ($val)
                $mids = array_merge($mids, array_map('intval', explode(',', $val)));
        }

        return array_values(array_filter(array_unique($mids), function($e) use ($all) { return in_array($e, $all); }));
    }

    /**
     * 获取角色的全部菜单IDs
     *
     * @param array $rids
     * @param bool  $support
     *
     * @return array|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getRolesMids(array $rids = [], $support = false)
    {
        $mids = [];
        $list = RoleModel::whereIn('rid', $rids)->where('status', 1)->orderBy('rid', 'desc')->get('mids')->pluck('mids')->toArray();
        $all = MenuModel::where('status', 1)->get('mid')->pluck('mid')->toArray();
        foreach ($list as $key => $val) {
            if ($val == '*') {
                if ($support)
                    return '*';
                $mids = array_merge($mids, $all);
                break;
            }
            else if ($val)
                $mids = array_merge($mids, array_map('intval', explode(',', $val)));
        }

        return array_values(array_filter(array_unique($mids), function($e) use ($all) { return in_array($e, $all); }));
    }

    /**
     * 获取用户的全部菜单详情
     *
     * @return array //['menu'=>...,'page'=>...,'action'=>...]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserMenu():array
    {
        //  有数据则直接返回
        if ($this->userMenu)
            return $this->userMenu;
        //  获取
        $menu = $page = $action = [];
        if (config('admin.access')) {
            //  获取用户
            $user = UserModel::where('uid', $this->uid)->where('status', 1)->first();
            if (!$user)
                return compact('menu', 'page', 'action');
            $mids = self::getRolesMids(self::getUserRids($this->uid));
            $list = $mids == '*' ? MenuModel::where('status', 1)->get() : MenuModel::whereIn('mid', $mids)->where('status', 1)->get();
        }
        else $list = MenuModel::where('status', 1)->get();
        //  分组
        foreach ($list as $val) {
            //  是否根节点
            $val['root'] = $val['upId'] ? 0 : 1;
            switch ($val['type']) {
                //  菜单
                case 'menu':
                    $menu[$val['mid']] = $val;
                    break;
                //  页面
                case 'page':
                    $page[$val['mid']] = $val;
                    break;
                //  操作
                case 'action':
                    $action[$val['mid']] = $val;
                    break;
            }
        }

        //  返回数据
        return $this->userMenu = compact('menu', 'page', 'action');
    }

    /**
     * 生成导航结构
     *
     * @param     $data
     * @param int $upId
     * @param int $deep
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessTree($data, int $upId = 0, int $deep = 0)
    {
        $tree = [];
        foreach ($data as $item) {
            if ($item['upId'] == $upId) {
                $item['deep'] = $deep;
                $tree[] = [
                        'icon' => $item['icon'],
                        'title' => self::autoLang($item['title']),
                        'path' => $item['path']
                    ] + ($item['type'] == 'menu' ? ['list' => self::getAccessTree($data, $item['mid'], $deep + 1)] : []);
            }
        }

        return $tree;
    }

    /**
     * 获取导航深度并生成树标题
     *
     * @param        $data
     * @param int    $mid
     * @param null   $val
     * @param string $pre
     *
     * @return array|\Illuminate\Support\Collection|null[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessTreeDeep($data, $mid = 0, $val = null, $pre = '')
    {
        if (!$mid) {
            $return = [];
            foreach ($data as $val) {
                if ($val['upId'])
                    continue;
                $val['title'] = self::autoLang($val['title']);
                $val['deep'] = 0;
                $val['deepTitle'] = $val['title'];
                $return = array_merge($return, self::getAccessTreeDeep($data, $val['mid'], $val));
            }

            return collect($return);
        }
        else {
            $val['icon'] = $val['icon'] ? : ($val['type'] == 'menu' ? 'fa fa-list' : ($val['type'] == 'page' ? 'far fa-circle' : 'fa fa-crosshairs'));
            $return = [$val];
            $count = 1;
            $childs = self::getAccessChilds($data, $mid);
            if ($childs) {
                $total = count($childs);
                foreach ($data as &$item) {
                    if ($item['upId'] != $mid)
                        continue;
                    $node = $fill = '';
                    if ($total == $count) {
                        $node .= '└';
                        $fill = '&nbsp;';
                    }
                    else {
                        $node .= '├';
                        $fill = '│';
                    }
                    $item['title'] = self::autoLang($item['title']);
                    $item['deep'] = $val['deep'] + 1;
                    $item['deepTitle'] = $pre . $node . ' ' . ($item['title'] ? $item['title'] : self::getLang(mb_substr($item['path'], mb_strripos($item['path'], '/') + 1)));
                    $return = array_merge($return, self::getAccessTreeDeep($data, $item['mid'], $item, $pre . $fill . '&nbsp;'));
                    $count++;
                }
            }

            return $return;
        }
    }

    /**
     * 获取导航深度
     *
     * @param      $data
     * @param int  $mid
     * @param null $val
     *
     * @return array|\Illuminate\Support\Collection|null[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessDeep($data, $mid = 0, $val = null)
    {
        if (!$mid) {
            $return = [];
            foreach ($data as $val) {
                if ($val['upId'])
                    continue;
                $val['deep'] = 0;
                $return = array_merge($return, self::getAccessDeep($data, $val['mid'], $val));
            }

            return collect($return);
        }
        else {
            $return = [$val];
            $count = 1;
            $childs = self::getAccessChilds($data, $mid);
            if ($childs) {
                foreach ($data as &$item) {
                    if ($item['upId'] != $mid)
                        continue;
                    $item['deep'] = $val['deep'] + 1;
                    $return = array_merge($return, self::getAccessDeep($data, $item['mid'], $item));
                    $count++;
                }
            }
        }

        return $return;
    }

    /**
     * 获取用户的导航结构
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserAccessTree():array
    {
        $menus = self::getUserMenu();

        return self::getAccessTree(collect(array_merge($menus['menu'], $menus['page']))->toArray());;
    }

    /**
     * 生成导航代码
     *
     * @param     $data
     * @param int $upId
     * @param int $deep
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessHtml($data, int $upId = 0, int $deep = 0)
    {
        $html = '';
        foreach ($data as $item) {
            if ($item['upId'] == $upId) {
                $item['deep'] = $deep;
                $item['badgeClass'] = !$item['badgeColor'] || stripos($item['badgeColor'], '#') === false ? ($item['badgeColor'] ? : 'badge-primary') : '';
                $item['badgeColor'] = $item['badgeClass'] ? '' : "background-color:{$item['badgeColor']};color:#FFFFFF";
                $item['badge'] = $item['badge'] ? "<span class='badge badge-pill float-right {$item['badgeClass']}' style='{$item['badgeColor']}'>{$item['badge']}</span>" : '';
                $item['tabs'] = $item['type'] == 'page' ? "data-tabs='{$item['mid']}' data-title='{$item['title']}'" : '';
                $html .= <<<EOF
<li class="{$item['active']}" deep="{$item['deep']}">
    <a href="{$item['path']}" {$item['tabs']} class="waves-effect {$item['arrow']}" aria-expanded="{$item['expanded']}">
        <i class="{$item['icon']}"></i>
        {$item['badge']}
        <span>{$item['title']}</span>
    </a>
EOF;
                if ($item['type'] == 'menu') {
                    $html .= "<ul class='mm-collapse {$item['show']}'>" . PHP_EOL;
                    $html .= self::getAccessHtml($data, $item['mid'], $deep + 1) . PHP_EOL;
                    $html .= '</ul>' . PHP_EOL;
                }
                $html .= '</li>' . PHP_EOL;
            }
        }

        return $html;
    }

    /**
     * 获取角色权限树
     *
     * @param     $data
     * @param int $rid
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getRoleTree($data, $rid = 0):array
    {
        $tree = [];
        $allow = $rid ? self::getRoleMids($rid) : [];
        foreach ($data as $item) {
            //  获取子节点
            $childs = $item['type'] == 'page' ? self::getAccessChilds($data, $item['mid']) : [];
            //  如果是页面并且有子节点则做特殊处理
            if ($item['type'] == 'page' && $childs) {
                $tree[] = [
                    'id' => $item['mid'],
                    'parent' => $item['upId'] ? : '#',
                    'icon' => $item['icon'],
                    'text' => $item['title'],
                    'state' => ['selected' => false]
                ];
                //  页面选择或者有子节点被选择则标记选择
                $selected = in_array($item['mid'], $allow);
                if (!$selected) {
                    foreach ($childs as $child) {
                        if (in_array($child['mid'], $allow)) {
                            $selected = true;
                            break;
                        }
                    }
                }
                $tree[] = [
                    'id' => ' ' . $item['mid'], //在ID前面补个空格来区分，实际保存时会trim掉空格，表示view等价页面选择
                    'parent' => $item['mid'],
                    'icon' => 'fa fa-eye',
                    'text' => self::getLang('view'),
                    'state' => ['selected' => $selected]
                ];
            }
            else {
                $tree[] = [
                    'id' => $item['mid'],
                    'parent' => $item['upId'] ? : '#',
                    'icon' => $item['icon'],
                    'text' => $item['title'] ? self::autoLang($item['title']) : self::getLang(mb_substr($item['path'], mb_strripos($item['path'], '/') + 1)),
                    'state' => ['selected' => in_array($item['mid'], $allow)]
                ];
            }
        }

        return $tree;
    }

    /**
     * 搜寻导航父类
     *
     * @param      $data
     * @param      $mid
     * @param bool $self
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessParents($data, $mid, $self = false)
    {
        $node = null;
        $list = [];
        foreach ($data as $item) {
            if ($item['mid'] == $mid) {
                if ($self)
                    $list[] = $item;
                $node = $item;
                break;
            }
        }
        if ($node)
            $list = array_merge($list, self::getAccessParents($data, $node['upId'], true));

        return $list;
    }

    /**
     * 搜寻导航子类
     *
     * @param $data
     * @param $mid
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessChilds($data, $mid)
    {
        $list = [];
        foreach ($data as $item) {
            if (!isset($item['mid']))
                continue;
            if ($item['upId'] == $mid) {
                $list[$item['mid']] = $item;
                $list = array_merge($list, self::getAccessChilds($data, $item['mid']));
            }
        }

        return $list;
    }

    /**
     * 生成导航属性
     *
     * @param     $all
     * @param     $origin
     * @param     $badge
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getAccessAttribute($all, $origin, $badge)
    {
        $data = collect($origin)->toArray();
        //  子节点有内容的话自动填入主节点来保证菜单正常展开
        $mids = collect($data)->pluck('mid')->toArray();
        foreach ($mids as $mid) {
            $parents = self::getAccessParents($all, $mid, false);
            foreach ($parents as $item) {
                if (!in_array($item['mid'], $mids)) {
                    $mids[] = $item['mid'];
                    $data[] = $item;
                }
            }
        }
        //  生成格式
        foreach ($data as &$item) {
            //  过滤操作和重复处理
            if ($item['type'] == 'action' || (is_string($item['path']) && strpos($item['path'], 'http') === 0))
                continue;
            //  统计
            $item['badge'] = $item['badge'] ?? 0;
            foreach ($badge as $k => $c)
                if ($item['path'] == $k) {
                    $c = is_array($c) ? $c : [$c];
                    $item['badge'] += $c[0];
                    $item['badgeColor'] = $c[1] ?? $item['badgeColor'];
                }
            //  标题
            $item['title'] = self::autoLang($item['title']);
            //  路径
            $item['path'] = $item['type'] == 'menu' ? 'javascript:' : admin_url($item['path']);
            //  箭头
            $item['arrow'] = $item['type'] == 'menu' ? 'has-arrow' : '';
            //  展开
            $item['expanded'] = $item['expanded'] ?? '';
            $item['show'] = $item['show'] ?? '';
            //  图标
            $item['icon'] = $item['icon'] ? : ($item['type'] == 'menu' ? 'fa fa-list' : 'far fa-circle');
            //  当前
            $item['active'] = $item['type'] == 'page' && $item['path'] == url()->current() ? 'mm-active' : '';
            //  父类
            $parents = $item['type'] == 'page' && $item['upId'] ? self::getAccessParents($data, $item['mid'], false) : [];
            foreach ($data as &$v) {
                if (in_array($v['mid'], collect($parents)->pluck('mid')->toArray())) {
                    $v['badge'] = $v['badge'] ?? 0;
                    $v['badge'] += $item['badge'];
                    $v['badgeColor'] = $v['badgeColor'] ? : $item['badgeColor'];
                    if ($item['active']) {
                        $v['expanded'] = 'true';
                        $v['show'] = 'mm-show';
                    }
                }
            }
            //  面包屑数组
            if ($item['active']) {
                foreach (array_reverse($parents) as $parent)
                    $this->breadcrumb[$parent['mid']] = $parent;
                $this->breadcrumb[$item['mid']] = $item;
            }
        }

        return $data;
    }

    /**
     * 获取面包屑导航
     *
     * @param bool $isDetails
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getBreadcrumb($isDetails = false)
    {
        if (!$this->breadcrumb) {
            $url = '';
            foreach (explode('/', request()->path()) as $index => $title) {
                $url .= '/' . $title;
                $this->breadcrumb[$index] = ['mid' => $index, 'path' => 'javascript:', 'title' => Str::studly($title)];
            }
        }

        foreach ($this->breadcrumb as $mid => $item)
            $this->breadcrumb[$mid]['title'] = self::autoLang($item['title']);

        if ($isDetails)
            return $this->breadcrumb;

        return collect($this->breadcrumb)->values()->pluck('title')->toArray();
    }

    /**
     * 获取通知内容
     *
     * @param bool $isDetails
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getNotification()
    {
        foreach ($this->notification as $index => $item) {
            if (!is_array($item))
                $this->notification[$index] = $item = [];
            $this->notification[$index]['theme'] = ($item['theme'] ?? null) ? : 'success';
            $this->notification[$index]['icon'] = ($item['icon'] ?? null) ? : 'fas fa-exclamation';
            $this->notification[$index]['title'] = self::autoLang($item['title'] ?? '');
            $this->notification[$index]['content'] = ($item['content'] ?? '') ? (is_array($item['content']) ? self::autoLang($item['content'][0] ?? '', $item['content'][1] ?? []) : self::autoLang($item['content'])) : '';
            $this->notification[$index]['path'] = ($item['path'] ?? null) ? : 'javascript:';
        }

        return $this->notification;
    }

    /**
     * 获取用户的导航信息
     *
     * @param bool $attr
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserMenuFormat($attr = true):array
    {
        $menus = self::getUserMenu();
        $data = bomber()->arrayReorder(array_merge($menus['menu'], $menus['page'], $menus['action']), 'root', SORT_DESC, 'weight', SORT_DESC, 'mid', SORT_ASC);

        return $attr ? self::getAccessAttribute(MenuModel::where('status', 1)->get(), $data, $this->badge) : $data;
    }

    /**
     * 获取用户的导航代码
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserMenuHtml():string
    {
        return self::getAccessHtml(self::getAccessAttribute(MenuModel::where('status', 1)->get(), self::getUserMenuFormat(false), $this->badge));
    }

    /**
     * 设置检查路径前置
     *
     * @param string $pre
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setPathPre(string $pre = '')
    {
        $this->pathPre = $pre;

        return $this;
    }

    /**
     * 获取路径
     *
     * @param string $path
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function path(string $path)
    {
        return admin_url($this->pathPre . '/' . $path);
    }

    /**
     * 路由请求权限拦截判断
     *
     * @param string $action
     * @param array  $except
     * @param array  $assgin
     *
     * @return bool
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function intercept(string $action, array $except = [], array $assgin = [])
    {
        //  如果标记了通配则直接通过
        if (in_array('*', $except))
            return true;
        //  如果在排除规则也通过
        if (in_array($action, $except))
            return true;
        //  是否指定了权限分配
        foreach ($assgin as $parent => $childs) {
            //  正常是数组，也兼容单字符串
            $childs = is_array($childs) ? $childs : [$childs];
            //  匹配父指派，可以是通配也可以是当前Action
            if (in_array('*', $childs) || in_array($action, $childs)) {
                //  如果父指派排除则通过
                if (in_array($parent, $except))
                    return true;
                //  特殊权限判断（index为父指派）
                $needle = $action;
                $replace = $parent;
                if (in_array($parent, ['', '/', 'index'])) {
                    $needle = "/{$action}";
                    $replace = '';
                }
                //  如果父指派通过则通过
                if (self::check(bomber()->strReplaceOnce($needle, $replace, url()->current(), 'post')))
                    return true;
            }
        }

        //  在规则中通过检查
        return self::check(url()->current());
    }

    /**
     * 获取用户对操作的权限
     *
     * @param string $path
     * @param bool   $pre
     * @param mixed  $content
     *
     * @return null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function action(string $path = '', $pre = false, $content = true)
    {
        $list = self::getUserMenu();
        foreach ($list['action'] as $item) {
            if ($item['path'] == str_replace(admin_url() . '/', '', $pre ? $this->pathPre . '/' . $path : $path))
                return $content;
        }

        return null;
    }

    /**
     * 获取用户对页面的权限
     *
     * @param string $path
     *
     * @return null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function page(string $path = '')
    {
        $list = self::getUserMenu();
        foreach ($list['page'] as $item) {
            if ($item['path'] == str_replace(admin_url() . '/', '', $path))
                return true;
        }

        return null;
    }

    /**
     * 获取用户对操作或页面的权限
     *
     * @param string $path
     * @param bool   $pre
     * @param bool   $content
     *
     * @return bool|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function check(string $path = '', $pre = false, $content = true)
    {
        //  检查操作
        $action = self::action($path, $pre, $content);
        if ($action !== null)
            return $action;

        //  检查页面
        return self::page($path);
    }
}
