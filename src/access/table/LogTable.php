<?php

namespace Demon\AdminLaravel\access\table;

use Demon\AdminLaravel\access\model\AllotModel;
use Demon\AdminLaravel\access\model\LogModel;
use Demon\AdminLaravel\access\model\RoleModel;
use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\access\Service;
use Demon\AdminLaravel\DBTable;
use Demon\AdminLaravel\Spreadsheet;
use Illuminate\Support\Facades\DB;

class LogTable extends DBTable
{
    public function __construct()
    {
        $this->access = app('admin')->access->setPathPre('admin/access/log');
        parent::__construct();
    }

    public function setConfig()
    {
        return ['batch' => true, 'key' => 'lid', 'reorder' => ['lid', 'desc']];
    }

    public function setButton()
    {
        return [
            'clear' => $this->access->action('clear', true, ['class' => 'btn btn-dark', 'text' => $this->access->getLang('clear'), 'icon' => 'fa fa-bomb', 'modal' => $this->access->getLang('clear_log')]),
            'del' => $this->access->action('del', true, ['class' => 'btn btn-danger', 'text' => $this->access->getLang('batch_del'), 'icon' => 'fa fa-trash-alt']),
            'export' => $this->access->action('export', true, ['class' => 'btn btn-success', 'text' => $this->access->getLang('export'), 'icon' => 'fa fa-file-download']),
        ];
    }

    public function setSearch()
    {
        return [
            ['data' => 'b.uid', 'title' => 'UID', 'where' => 'in'],
            ['data' => 'b.username', 'title' => $this->access->getLang('username'), 'where' => 'like'],
            ['name' => 'userRemark', 'data' => 'b.remark', 'title' => $this->access->getLang('userRemark'), 'where' => 'like'],
            ['data' => 'a.tag', 'title' => $this->access->getLang('tag'), 'where' => 'like'],
            ['data' => 'a.remark', 'title' => $this->access->getLang('remark'), 'where' => 'like'],
            ['data' => 'a.ip', 'title' => $this->access->getLang('ip'), 'type' => 'text', 'where' => 'between', 'format' => 'ip2long'],
            ['data' => 'a.userAgent', 'title' => $this->access->getLang('userAgent'), 'where' => 'like'],
            ['data' => 'a.path', 'title' => $this->access->getLang('path'), 'where' => 'like'],
            ['data' => 'a.soleCode', 'title' => $this->access->getLang('soleCode'), 'where' => 'like'],
            ['data' => 'a.createTime', 'title' => $this->access->getLang('createTime'), 'type' => 'time', 'where' => 'range', 'format' => 'mstime', 'attr' => ['data-time' => null]],
        ];
    }

    public function setOrder()
    {
        return ['a.lid' => 'desc'];
    }

    public function setColumn()
    {
        return [
            ['data' => 'a.createTime', 'title' => $this->access->getLang('createTime'), 'reorder' => true],
            ['data' => 'b.uid', 'title' => $this->access->getLang('username'), 'width' => '10%', 'reorder' => true],
            ['data' => 'userRemark', 'origin' => 'b.remark as userRemark', 'title' => $this->access->getLang('userRemark'), 'width' => '10%', 'search' => true],
            ['data' => 'a.tag', 'title' => $this->access->getLang('tag'), 'search' => true],
            ['data' => 'a.path', 'title' => $this->access->getLang('path'), 'search' => true, 'action' => true],
            ['data' => 'a.remark', 'title' => $this->access->getLang('remark'), 'search' => true],
            ['data' => 'a.ip', 'title' => $this->access->getLang('ip'), 'search' => true],
            ['data' => 'a.soleCode', 'title' => $this->access->getLang('soleCode'), 'search' => true],
            ['data' => '_action', 'title' => $this->access->getLang('action'), 'action' => 'group'],
        ];
    }

    public function setField($field)
    {
        return $field + ['a.*', 'b.username'];
    }

    public function setQuery()
    {
        return LogModel::from((new LogModel())->getTable(), 'a')->leftJoin((new UserModel())->getTable() . ' as b', 'b.uid', '=', 'a.uid', 'left');
    }

    public function setFormat(&$data)
    {
        return [
            'uid' => function($val) { return "[{$val->uid}]{$val->username}"; },
            'path' => function($val) {
                return $val->path ? admin_html('input', ['readonly' => true, 'value' => $val->path], 'form-control input-sm', [
                    'class' => 'input-group input-group-sm',
                    'append' => "<button class='btn btn-secondary clipboard' data-clipboard-text='{$val->path}'><i class='far fa-clipboard'></i></button>",
                    'attr' => ['style' => 'min-width:120px']
                ]) : null;
            },
            'ip' => function($val) { return $val->ip ? long2ip($val->ip) : null; },
            'createTime' => function($val) { return $val->createTime ? msdate('Y-m-d H:i:s', $val->createTime) : null; },
            '_action' => [
                'type' => 'add',
                'callback' => function($val) {
                    return $this->access->action('info', true, admin_button('info', ['modal' => $this->access->getLang('log_info')])) .
                        $this->access->action('del', true, $val->system ? '' : admin_button('del', ['modal' => $this->access->getLang('del_log')]));
                }
            ],
        ];
    }

    public function export()
    {
        $max = 10000;
        if ($this->count() > $max)
            abort(DEMON_CODE_LARGE, $this->access->getLang('export_max_length', ['length' => $max]));
        $this->format = [
            'path' => function($val) { return $val->path; },
            'uid' => function($val) { return $val->uid; },
            '_action' => ['type' => 'remove']
        ];
        $list = $this->getFormat($this->get());
        $column = [
            'UID', $this->access->getLang('username'), $this->access->getLang('userRemark'),
            $this->access->getLang('createTime') => 30, $this->access->getLang('tag') => 30, $this->access->getLang('method'), $this->access->getLang('path') => 50,
            $this->access->getLang('content') => 50, $this->access->getLang('arguments') => 50, $this->access->getLang('remark') => 50,
            $this->access->getLang('ip'), $this->access->getLang('userAgent') => 50, $this->access->getLang('soleCode') => 30,
        ];
        $data = [];
        foreach ($list as $key => $val) {
            $data[] = [
                $val->uid, $val->username, $val->userRemark,
                $val->createTime, $val->tag, $val->method, $val->path,
                $val->content, $val->arguments, $val->remark,
                $val->ip, $val->userAgent, $val->soleCode
            ];
        }

        return (new Spreadsheet())->download(app('admin')->__('base.menu.admin_access_log') . '.xls', ['sheet' => $this->access->getLang('log'), 'column' => $column], $data);
    }
}
