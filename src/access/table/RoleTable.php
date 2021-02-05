<?php

namespace Demon\AdminLaravel\access\table;

use Demon\AdminLaravel\access\model\RoleModel;
use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\access\Service;
use Demon\AdminLaravel\DBTable;
use Illuminate\Support\Facades\DB;

class RoleTable extends DBTable
{
    public function __construct()
    {
        $this->access = app('admin')->access->setPathPre('admin/access/role');
        $this->store = RoleModel::fieldStore();
        parent::__construct();
    }

    public function setConfig()
    {
        return ['batch' => true, 'key' => 'rid', 'reorder' => ['system', 'desc'], 'search' => false, 'searchPanel' => false];
    }

    public function setButton()
    {
        return [
            'add' => $this->access->action('add', true, ['class' => 'btn btn-success', 'text' => $this->access->getLang('add_role'), 'icon' => 'fa fa-plus']),
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

    public function setColumn()
    {
        return [
            ['data' => 'deepName', 'title' => $this->access->getLang('name')],
            ['data' => 'remark', 'title' => $this->access->getLang('remark')],
            ['data' => 'status', 'title' => $this->access->getLang('status'), 'action' => true],
            ['data' => '_action', 'title' => $this->access->getLang('action'), 'action' => 'group'],
        ];
    }

    public function setField($field)
    {
        return ['*'];
    }

    public function setQuery()
    {
        return RoleModel::query();
    }

    public function setFormat(&$data)
    {
        return [
            'deepName' => function($val) { return $this->access->autoLang($val->name); },
            'status' => function($val) { return admin_html('switch', $this->access->action('status', true) && !$val->system ? ['action' => 'status'] : ['readonly' => true], $val->status); },
            '_action' => [
                'type' => 'add',
                'callback' => function($val) {
                    return $val->system ? '' : $this->access->action('edit', true, admin_button('edit')) . $this->access->action('del', true, admin_button('del'));
                }
            ],
        ];
    }
}
