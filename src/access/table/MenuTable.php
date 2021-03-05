<?php

namespace Demon\AdminLaravel\access\table;

use Demon\AdminLaravel\access\model\MenuModel;
use Demon\AdminLaravel\access\model\RoleModel;
use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\access\Service;
use Demon\AdminLaravel\DBTable;
use Illuminate\Support\Facades\DB;

class MenuTable extends DBTable
{
    public function __construct()
    {
        $this->access = app('admin')->access->setPathPre('admin/access/menu');
        $this->store = MenuModel::fieldStore();
        parent::__construct();
    }

    public function setConfig()
    {
        return [
            'batch' => true,
            'key' => 'mid',
            'reorder' => ['upId', 'asc'],
            'search' => false,
            'searchPanel' => false,
            'pagination' => false,
            'classes' => 'table table-bordered table-hover table-nowrap',
            'reorderableRows' => true,
            'useRowAttrFunc' => true,
            'dragHandle' => '[action="weight"]',
        ];
    }

    public function setButton()
    {
        return [
            'add' => $this->access->action('add', true, ['class' => 'btn btn-success', 'text' => $this->access->getLang('add_menu'), 'icon' => 'fa fa-plus']),
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
            'fold' => ['class' => 'btn btn-secondary', 'text' => $this->access->getLang('menu_fold'), 'icon' => 'fa fa-folder-open', 'attr' => ['status' => 'off']],
        ];
    }

    public function setOrder()
    {
        return ['weight' => 'desc'];
    }

    public function setColumn()
    {
        return [
            ['data' => 'typeName', 'title' => $this->access->getLang('type')],
            ['data' => 'icon', 'title' => $this->access->getLang('icon')],
            ['data' => 'deepTitle', 'title' => $this->access->getLang('title')],
            ['data' => 'path', 'title' => $this->access->getLang('path'), 'action' => true],
            ['data' => 'remark', 'title' => $this->access->getLang('remark')],
            ['data' => 'status', 'title' => $this->access->getLang('status'), 'action' => true],
            ['data' => '_action', 'title' => $this->access->getLang('action'), 'action' => 'group'],
        ];
    }

    public function setField($field)
    {
        return ['*', 'type as typeName'];
    }

    public function setQuery()
    {
        return MenuModel::query();
    }

    public function setFormat(&$data)
    {
        $data = $this->access->getAccessTreeDeep($data);

        return [
            'typeName' => function($val) { return $this->store['type'][$val->type] ?? null; },
            'icon' => function($val) { return $val->icon ? admin_html('fast', '', [], 'i', $val->icon) : null; },
            'path' => function($val) {
                return $val->path ? admin_html('input', ['readonly' => true, 'value' => $val->path], 'form-control input-sm', [
                    'class' => 'input-group input-group-sm',
                    'append' => "<button class='btn btn-secondary clipboard' data-clipboard-text='{$val->path}'><i class='far fa-clipboard'></i></button>",
                    'attr' => ['style' => 'min-width:120px']
                ]) : null;
            },
            'deepTitle' => function($val) { return $val->deepTitle; },
            'status' => function($val) { return admin_html('switch', $this->access->action('status', true) && !$val->system ? ['action' => 'status'] : ['readonly' => true], $val->status); },
            '_action' => function($val) {
                return $val->system ? '' :
                    $this->access->action('weight', true, admin_button('weight', '', ['icon' => 'fa fa-arrows-alt', 'attr' => ['title' => $this->access->getLang('drag_weight'), 'data-container' => '.bootstrap-table']])) .
                    $this->access->action('add', true, $val->type != 'action' ? admin_button('add', ['modal' => $this->access->getLang('add_menu')]) : '') .
                    $this->access->action('edit', true, admin_button('edit', ['modal' => $this->access->getLang('edit_menu')])) .
                    $this->access->action('del', true, admin_button('del', ['modal' => $this->access->getLang('del_menu')]));
            },
        ];
    }
}
