<?php

namespace Demon\AdminLaravel\access\table;

use Demon\AdminLaravel\access\model\AllotModel;
use Demon\AdminLaravel\access\model\RoleModel;
use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\access\Service;
use Demon\AdminLaravel\DBTable;
use Illuminate\Support\Facades\DB;

class UserTable extends DBTable
{
    public function __construct()
    {
        $this->access = app('admin')->access->setPathPre('admin/access/user');
        $this->store = UserModel::fieldStore();
        parent::__construct();
    }

    public function setConfig()
    {
        return ['batch' => true, 'key' => 'uid', 'reorder' => ['uid', 'desc']];
    }

    public function setButton()
    {
        return [
            'add' => $this->access->action('add', true, ['class' => 'btn btn-success', 'text' => $this->access->getLang('add_user'), 'icon' => 'fa fa-plus']),
            'batch' => $this->access->action('batch', true, [
                'class' => 'btn btn-primary',
                'text' => $this->access->getLang('batch'),
                'icon' => 'fa fa-list',
                'list' => [
                    $this->access->action('status', true, ['text' => $this->access->getLang('batch_on'), 'action' => 'on']),
                    $this->access->action('status', true, ['text' => $this->access->getLang('batch_off'), 'action' => 'off']),
                    $this->access->action('del', true, ['text' => $this->access->getLang('batch_del'), 'action' => 'del']),
                ]
            ]),
        ];
    }

    public function setSearch()
    {
        return [
            ['data' => 'a.uid', 'title' => 'UID', 'where' => 'in'],
            ['data' => 'a.username', 'title' => $this->access->getLang('username'), 'where' => 'like'],
            ['data' => 'a.nickname', 'title' => $this->access->getLang('nickname'), 'where' => 'like'],
            [
                'data' => 'b.role', 'title' => $this->access->getLang('role'), 'type' => 'select', 'placeholder' => $this->access->getLang('option_all'),
                'option' => ['bind' => 'rid', 'title' => 'deepName', 'list' => $this->store['role']], 'attr' => ['data-select' => true],
                'where' => function($value, $field) { return function($query) use ($field, $value) { $query->whereRaw("FIND_IN_SET({$value},{$field})"); }; },
            ],
            ['data' => 'a.remark', 'title' => $this->access->getLang('remark'), 'where' => 'like'],
            ['data' => 'a.loginTime', 'title' => $this->access->getLang('loginTime'), 'type' => 'time', 'where' => 'range', 'format' => 'mstime', 'attr' => ['data-time' => null]],
            ['data' => 'a.activeTime', 'title' => $this->access->getLang('activeTime'), 'type' => 'time', 'where' => 'range', 'format' => 'mstime', 'attr' => ['data-time' => null]],
            ['data' => 'a.status', 'title' => $this->access->getLang('status'), 'type' => 'select', 'placeholder' => $this->access->getLang('option_all'), 'option' => ['list' => $this->store['status']]],
        ];
    }

    public function setOrder()
    {
        return ['a.uid' => 'asc'];
    }

    public function setColumn()
    {
        return [
            ['data' => 'a.uid', 'title' => 'UID', 'reorder' => true],
            ['data' => 'a.username', 'title' => $this->access->getLang('username'), 'width' => '10%', 'search' => true],
            ['data' => 'a.nickname', 'title' => $this->access->getLang('nickname'), 'width' => '10%', 'search' => true],
            ['data' => 'a.avatar', 'title' => $this->access->getLang('avatar'), 'width' => '60px', 'action' => true],
            ['data' => 'role', 'title' => $this->access->getLang('role'), 'custom' => true],
            ['data' => 'a.remark', 'title' => $this->access->getLang('remark'), 'width' => '10%', 'search' => true],
            ['data' => 'a.activeTime', 'title' => $this->access->getLang('activeTime'), 'reorder' => true],
            ['data' => 'a.loginTime', 'title' => $this->access->getLang('loginTime'), 'reorder' => true],
            ['data' => 'a.status', 'title' => $this->access->getLang('status'), 'action' => true, 'reorder' => true],
            ['data' => '_action', 'title' => $this->access->getLang('action'), 'action' => 'group'],
        ];
    }

    public function setField($field)
    {
        return $field + ['b.role', 'a.system'];
    }

    public function setQuery()
    {
        return UserModel::from((new UserModel())->getTable(), 'a')->where('a.status', '>=', 0)->joinSub(AllotModel::select(DB::raw('GROUP_CONCAT(rid) as role,uid'))->groupBy('uid'), 'b', 'b.uid', '=', 'a.uid', 'left');
    }

    public function setFormat($data)
    {
        return [
            'avatar' => function($val) { return admin_html('image', app('admin')->getUserAvatar($val->avatar, $val->nickname), ['name' => $val->nickname]); },
            'role' => function($val) {
                $html = '';
                foreach (explode(',', $val->role) as $k => $v) {
                    if ($k >= 2) {
                        $html .= admin_html('fast', '...', [], 'span', 'badge badge-secondary');
                        break;
                    }
                    if ($role = $this->store['role']->where('rid', $v)->first())
                        $html .= admin_html('fast', $role->deepName, [], 'span', 'badge badge-info');
                }

                return $html ? : null;
            },
            'activeTime' => function($val) { return $val->activeTime ? msdate('Y-m-d H:i:s', $val->activeTime) : null; },
            'loginTime' => function($val) { return $val->loginTime ? msdate('Y-m-d H:i:s', $val->loginTime) : null; },
            'status' => function($val) { return admin_html('switch', $this->access->action('status', true) && !$val->system ? ['action' => 'status'] : ['readonly' => true], $val->status); },
            '_action' => function($val) {
                return $this->access->action('info', true, admin_button('info', ['modal' => $this->access->getLang('user_info')])) .
                    $this->access->action('edit', true, admin_button('edit', ['modal' => $this->access->getLang('edit_user')])) .
                    $this->access->action('del', true, $val->system ? '' : admin_button('del', ['modal' => $this->access->getLang('del_user')]));
            },
        ];
    }
}
