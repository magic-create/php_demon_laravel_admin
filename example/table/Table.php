<?php

namespace App\Http\Tables;

use Demon\AdminLaravel\DBTable;
use Demon\AdminLaravel\Spreadsheet;

class Table extends DBTable
{
    public function __construct()
    {
        $this->store = [
            'sex' => [-1 => '女', 0 => '未知', 1 => '男'],
            'level' => (new LevelRedis)->_get_all(),
            'vip' => (new VipRedis)->_get_all(),
        ];
        parent::__construct();
    }

    public function setConfig()
    {
        return ['batch' => true, 'sortName' => 'level', 'sortOrder' => 'desc'];
    }

    public function setButton()
    {
        return [
            'export' => ['class' => 'btn btn-info', 'text' => '服务端导出', 'icon' => 'fa fa-file-download'],
            'batch' => ['class' => 'btn btn-warning', 'icon' => 'fa fa-list', 'title' => '批量调试'],
        ];
    }

    public function setSearch()
    {
        return [
            ['data' => 'a.uid', 'title' => 'UID', 'where' => 'in'],
            ['data' => 'a.code', 'title' => '编号', 'where' => 'like'],
            ['data' => 'b.sex', 'title' => '性别', 'type' => 'select', 'rule' => ['data' => $this->store['sex']]],
            ['data' => 'b.level', 'title' => '等级', 'type' => 'select', 'where' => 'range', 'rule' => ['keys' => 'name', 'data' => $this->store['level']], 'attr' => ['data-select' => true]],
            ['data' => 'b.vip', 'title' => '贵族', 'type' => 'select', 'rule' => ['keys' => 'name', 'data' => $this->store['vip']]],
            ['data' => 'c.bean', 'title' => '魔豆', 'where' => 'range'],
            ['data' => 'c.gold', 'title' => '金币', 'where' => 'range'],
            ['data' => 'c.grow', 'title' => '魔气', 'where' => 'range'],
            ['data' => 'c.exp', 'title' => '功勋', 'where' => 'range'],
            ['data' => 'a.createTime', 'title' => '注册时间', 'type' => 'time', 'where' => 'range', 'rule' => ['format' => 'ms'], 'attr' => ['data-time' => 'datetime']],
            ['data' => 'b.nickname', 'title' => '昵称', 'where' => 'like', 'type' => 'nickname', 'value' => '黑'],
        ];
    }

    public function setOrder()
    {
        return ['a.uid' => 'desc'];
    }

    public function setColumn()
    {
        return [
            ['data' => 'a.uid', 'title' => 'UID', 'reorder' => true],
            ['data' => 'code', 'title' => '编号', 'reorder' => true],
            ['data' => 'nickname', 'title' => '昵称', 'width' => '10%', 'search' => true],
            ['data' => 'avatar', 'title' => '头像', 'width' => '60px'],
            ['data' => 'sex', 'title' => '性别'],
            ['data' => 'level', 'title' => '等级', 'reorder' => true],
            ['data' => 'vip', 'title' => '贵族', 'reorder' => true],
            ['data' => 'bean', 'title' => '魔豆', 'reorder' => true],
            ['data' => 'gold', 'title' => '金币', 'reorder' => true],
            ['data' => 'grow', 'title' => '魔气', 'reorder' => true],
            ['data' => 'exp', 'title' => '功勋', 'reorder' => true],
            ['data' => 'createTime', 'title' => '注册日期', 'reorder' => true],
            ['data' => 'activeTime', 'title' => '活跃日期', 'reorder' => true],
            ['data' => '_action', 'title' => '操作', 'action' => true, 'custom' => true],
        ];
    }

    public function setField()
    {
        return ['status'];
    }

    public function setQuery()
    {
        return (new MemberModel)->setTable('core_member as a')->leftJoin('core_member_data as b', 'b.uid', '=', 'a.uid')->leftJoin('core_member_credit as c', 'c.uid', '=', 'a.uid');
    }

    public function setStatis($query)
    {
        return [
            'gold' => (int)$query->sum('gold'),
            'bean' => (int)$query->sum('bean')
        ];
    }

    public function setFormat()
    {
        return [
            'avatar' => function($val) { return adminHtml('image', (new ConfigService)->format('avatar', $val->avatar)); },
            'sex' => function($val) { return adminHtml('fast', $this->store['sex'][$val->sex]); },
            'level' => function($val) { return adminHtml('fast', $this->store['level']->{$val->level}->name); },
            'vip' => function($val) { return adminHtml('fast', $this->store['vip']->{$val->vip}->name); },
            'createTime' => function($val) { return msdate('Y-m-d', $val->createTime); },
            'activeTime' => function($val) { return msdate('Y-m-d', $val->activeTime ? : $val->createTime); },
            '_action' => [
                'type' => 'add',
                'callback' => function() {
                    return adminTableButton('add', 'add', ['text' => '测试按钮']) .
                        adminTableButton('edit', 'edit') .
                        adminTableButton('del', 'del', ['title' => '测试按钮']) .
                        adminTableButton('get', 'get') .
                        adminTableButton('test');
                }
            ],
        ];
    }

    public function export($name = '用户信息')
    {
        $count = $this->getQuery(false)->count();
        if ($count > 1e5)
            return '导出数据较大，请精准搜索条件后导出';
        //  获取数据源
        $list = $this->query->orderBy('a.createTime', 'desc')->limit(100)->get();
        //  设置表头
        $title = ['UID', '编号', '昵称', '头像', '性别', '等级', '贵族', '魔豆', '金币', '魔气', '功勋', '注册时间', '活跃时间'];
        $width = [20, 20, 50, 30, 20, 20, 20, 20, 20, 20, 20, 50, 50];
        $format = ['string', 'string', 'string', 'image', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string'];
        $height = 30;
        //  遍历数据
        $data = [];
        foreach ($list as $key => $val) {
            $data[] = [
                $val->uid,
                $val->code,
                $val->nickname,
                (new ConfigService)->format('avatar', $val->avatar),
                $this->store['sex'][$val->sex],
                $this->store['level']->{$val->level}->name,
                $this->store['vip']->{$val->vip}->name,
                $val->bean,
                $val->gold,
                $val->grow,
                $val->exp,
                msdate('Y-m-d H:i:s', $val->createTime),
                msdate('Y-m-d H:i:s', $val->activeTime ? : $val->createTime),
            ];
        }

        //  导出文件
        return (new Spreadsheet())->download($name . '.xls', ['sheet' => $name] + compact('title', 'width', 'height', 'format'), $data);
    }
}
