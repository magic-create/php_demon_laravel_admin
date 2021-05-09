<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\DBTable;
use Demon\AdminLaravel\Spreadsheet;
use Illuminate\Support\Facades\DB;

class Table extends DBTable
{
    /**
     * Table constructor.
     */
    public function __construct()
    {
        //  设置字段内容
        $this->store = Service::fieldStore();
        parent::__construct();
    }

    /**
     * 设置配置
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setConfig()
    {
        return ['batch' => true, 'key' => Service::MasterKey, 'reorder' => ['uid', 'desc']];
    }

    /**
     * 设置按钮
     *
     * @return array|\string[][]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setButton()
    {
        return [
            'export' => ['class' => 'btn btn-info', 'text' => '服务端导出', 'icon' => 'fa fa-file-download'],
            'add' => ['class' => 'btn btn-success', 'text' => '新增用户', 'icon' => 'fa fa-plus'],
        ];
    }

    /**
     * 设置搜索
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setSearch()
    {
        $credit = [];
        foreach ($this->store['credit'] as $type => $v)
            $credit[] = ['data' => 'c_' . $type . '.value', 'name' => $v['alias'], 'title' => $v['name'], 'where' => 'range'];

        return array_merge([
            ['data' => 'a.uid', 'title' => 'UID', 'placeholder' => '精准UID，多个可用 , 分隔', 'where' => 'in'],
            ['data' => 'a.phone', 'title' => '手机号', 'where' => 'like'],
            ['data' => 'a.nickname', 'title' => '昵称', 'where' => 'like', 'type' => 'custom'],
            ['data' => 'a.sex', 'title' => '性别', 'type' => 'select', 'placeholder' => '请选择性别', 'option' => ['list' => $this->store['sex']]],
            [
                'data' => 'a.avatar', 'title' => '头像', 'type' => 'select', 'placeholder' => '不限', 'option' => ['list' => $this->store['avatar']],
                'where' => function($value, $field) {
                    return $value == 'have' ?
                        function($query) use ($field) { $query->whereNotNull($field)->orWhere($field, '!=', ''); } :
                        function($query) use ($field) { $query->whereNull($field)->orWhere($field, ''); };
                }
            ],
            ['data' => 'a.code', 'title' => '邀请码', 'where' => 'like'],
            ['data' => 'a.inviteUid', 'title' => '邀请人UID', 'placeholder' => '精准UID，多个可用 , 分隔', 'where' => 'in', 'value' => arguer('inviteUid')],
            ['data' => 'a.level', 'title' => '等级', 'type' => 'select', 'value' => [0, count($this->store['level']) - 2], 'placeholder' => ['不限制最小等级', '不限制最大等级'], 'where' => 'range', 'option' => ['title' => 'name', 'list' => $this->store['level']], 'attr' => ['data-select' => true]],
            ['data' => 'a.hobby', 'title' => '爱好', 'type' => 'select', 'placeholder' => '全部', 'option' => ['bind' => 'id', 'title' => 'title', 'list' => $this->store['hobby']], 'attr' => ['data-select' => true]],
            [
                'data' => 'a.type', 'title' => '分类',
                'type' => 'linkage', 'option' => ['level' => 3, 'placeholderStatus' => true, 'placeholderList' => ['请选择一级分类', '请选择二级分类', '请选择三级分类'], 'bindKey' => 'id', 'parentKey' => 'upId', 'tree' => false, 'titleKey' => 'name', 'list' => $this->store['type']],
                'attr' => ['data-select' => true],
                'where' => function($value, $field) {
                    if (!$value[1])
                        return null;
                    else if ($value[3])
                        $list = [$value[3]];
                    else if ($value[2])
                        $list = $this->store['type']->where('upId', $value[2])->pluck('id')->toArray();
                    else $list = $this->store['type']->whereIn('upId', $this->store['type']->where('upId', $value[1])->pluck('id')->toArray())->pluck('id')->toArray();

                    return $list ? function($query) use ($field, $list) { $query->whereIn($field, $list); } : null;
                }
            ],
            ['data' => 'a.birthday', 'title' => '生日', 'type' => 'time', 'where' => 'range', 'attr' => ['data-time' => 'YYYY-MM-DD']],
            ['data' => 'a.activeTime', 'title' => '活跃时间', 'type' => 'time', 'where' => 'range', 'attr' => ['data-time' => null]],
            ['data' => 'a.signDate', 'title' => '签到日期', 'type' => 'time', 'where' => 'range', 'format' => function($value) { return date('Ymd', strtotime($value)); }, 'attr' => ['data-time' => 'YYYY-MM-DD']],
            ['data' => 'a.loginTime', 'title' => '登录时间', 'type' => 'time', 'where' => 'range', 'format' => 'time', 'attr' => ['data-time' => null]],
            ['data' => 'a.loginIpv4s', 'title' => '登录IP', 'where' => '%like'],
            ['data' => 'a.loginIpv4i', 'name' => 'loginIpv4', 'title' => '登录IP', 'type' => 'text', 'where' => 'between', 'format' => 'ip2long'],
            ['data' => 'a.createTime', 'title' => '注册时间', 'type' => 'time', 'where' => 'range', 'format' => 'mstime', 'attr' => ['data-time' => null]],
            ['data' => 'a.status', 'title' => '状态', 'type' => 'select', 'placeholder' => '全部', 'option' => ['list' => $this->store['status']]],
        ], $credit);
    }

    /**
     * 设置后置排序
     *
     * @return array|string[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setOrder()
    {
        return ['a.uid' => 'asc'];
    }

    /**
     * 设置字段
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setColumn()
    {
        $credit = [];
        foreach ($this->store['credit'] as $type => $v)
            $credit[] = ['data' => $v['alias'], 'origin' => 'c_' . $type . '.value', 'title' => $v['name'], 'action' => true, 'reorder' => true];

        return array_merge([
            ['data' => 'a.uid', 'title' => 'UID', 'reorder' => true],
            ['data' => 'a.phone', 'title' => '手机号', 'search' => true],
            ['data' => 'a.nickname', 'title' => '昵称', 'width' => '10%', 'search' => true],
            ['data' => 'a.code', 'title' => '邀请码', 'width' => '10%', 'search' => true],
            ['data' => 'c.inviteCount', 'title' => '已邀请人数', 'reorder' => true, 'action' => true],
            ['data' => 'a.inviteUid', 'title' => '邀请人信息', 'width' => '15%'],
            ['data' => 'a.avatar', 'title' => '头像', 'width' => '60px', 'action' => true],
            ['data' => 'a.sex', 'title' => '性别'],
            ['data' => 'a.level', 'title' => '等级', 'reorder' => true],
            ['data' => 'a.hobby', 'title' => '爱好'],
            ['data' => 'a.type', 'title' => '分类']
        ], $credit, [
            ['data' => 'a.birthday', 'title' => '生日', 'reorder' => true],
            ['data' => 'a.activeTime', 'title' => '活跃时间', 'reorder' => true],
            ['data' => 'a.signDate', 'title' => '签到日期', 'reorder' => true],
            ['data' => 'a.loginIpv4i', 'title' => '登录IP'],
            ['data' => 'a.loginTime', 'title' => '登录时间', 'reorder' => true],
            ['data' => 'a.createTime', 'title' => '注册日期', 'reorder' => true],
            ['data' => 'a.updateTime', 'title' => '更新日期', 'reorder' => true],
            ['data' => 'a.status', 'title' => '状态', 'action' => true, 'reorder' => true],
            ['data' => '_action', 'title' => '操作', 'action' => 'group'],
        ]);
    }

    /**
     * 设置字段
     *
     * @param array|string[]
     *
     * @return array|string[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setField($field)
    {
        return $field + ['a.data', 'b.nickname as inviteNickname'];
    }

    /**
     * 设置查询
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|DB|null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setQuery()
    {
        $query = DB::connection(Service::$connection)->table(Service::MasterModel . ' as a')->where('a.status', '>=', 0);
        $query->leftJoin(Service::MasterModel . ' as b', 'b.uid', '=', 'a.inviteUid');
        $inviteCount = DB::connection(Service::$connection)->table(Service::MasterModel)->select(DB::raw('count(1) as inviteCount,inviteUid'))->groupBy('inviteUid');
        $query->leftJoinSub($inviteCount, 'c', 'c.inviteUid', '=', 'a.uid');
        foreach ($this->store['credit'] as $type => $name) {
            $alias = 'c_' . $type;
            $query->leftJoin(Service::SlaveModel . ' as ' . $alias, function($query) use ($alias, $type) {
                $query->on($alias . '.uid', '=', 'a.uid')->where($alias . '.type', $type);
            });
        }

        return $query;
    }

    /**
     * 设置携带统计
     *
     * @param $query
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setStatis($query)
    {
        $credit = [];
        foreach ($this->store['credit'] as $type => $v)
            $credit[$v['name']] = bomber()->doublePrecision($query->sum('c_' . $type . '.value'), $v['decimals']);

        return $credit;
    }

    /**
     * 设置格式化
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setFormat()
    {
        $credit = [];
        foreach ($this->store['credit'] as $type => $v)
            $credit[$v['alias']] = function($val) use ($type, $v) {
                return admin_button('credit', '', [
                    'title' => "点击变更{$v['name']}",
                    'text' => bomber()->doublePrecision($val->{$v['alias']}, $v['decimals']),
                    'theme' => 'success',
                    'attr' => ['data-type' => $type, 'data-name' => $v['name']]
                ]);
            };

        return [
                'inviteCount' => function($val) { return $val->inviteCount ? admin_button('invited', '', ['title' => "点击查看列表", 'text' => $val->inviteCount, 'theme' => 'info']) : null; },
                'inviteUid' => function($val) { return $val->inviteUid ? "{$val->inviteNickname}[{$val->inviteUid}]" : null; },
                'avatar' => function($val) { return admin_html('image', $val->avatar ? : bomber()->strImage($val->nickname, 'svg', ['calc' => true, 'substr' => true]), ['name' => $val->nickname]); },
                'sex' => function($val) { return $this->store['sex'][$val->sex] ?? '未知'; },
                'level' => function($val) { return $this->store['level'][$val->level]->name ?? '未知'; },
                'hobby' => function($val) {
                    $html = '';
                    foreach (explode(',', $val->hobby) as $k => $v) {
                        if ($k >= 2) {
                            $html .= admin_html('fast', '...', [], 'span', 'badge badge-secondary');
                            break;
                        }
                        if ($hobby = $this->store['hobby']->where('id', $v)->first())
                            $html .= admin_html('fast', $hobby->title, [], 'span', 'badge badge-info');
                    }

                    return $html ? : null;
                },
                'type' => function($val) {
                    $data3 = $this->store['type']->where('id', $val->type)->first();
                    if (!$data3)
                        return null;
                    $data2 = $this->store['type']->where('id', $data3->upId)->first();
                    if (!$data2)
                        return null;
                    $data1 = $this->store['type']->where('id', $data2->upId)->first();
                    if (!$data1)
                        return null;

                    return admin_html()->fast($data3->name, ['data-toggle' => 'tooltip', 'title' => "{$data1->name} - {$data2->name} - {$data3->name}"], 'span');
                },
                'signDate' => function($val) { return $val->signDate ? date('Y-m-d', strtotime($val->signDate)) : null; },
                'loginIpv4i' => function($val) { return $val->loginIpv4i ? long2ip($val->loginIpv4i) : null; },
                'loginTime' => function($val) { return $val->loginTime ? date('Y-m-d H:i:s', $val->loginTime) : null; },
                'createTime' => function($val) { return $val->createTime ? msdate('Y-m-d', $val->createTime) : null; },
                'updateTime' => function($val) { return $val->updateTime ? msdate('Y-m-d', $val->updateTime) : null; },
                'status' => function($val) { return admin_html('switch', ['action' => 'status'], $val->status); },
                '_action' => [
                    'type' => 'add',
                    'callback' => function() {
                        return admin_button('edit', 'edit') .
                            admin_button('del', 'del', ['title' => '删除用户']) .
                            admin_button('get', 'get') .
                            admin_button('invite', '', ['title' => '模拟邀请注册', 'icon' => 'fa fa-hands-helping']);
                    }
                ],
            ] + $credit;
    }

    /**
     * 导出
     *
     * @param string $name
     *
     * @return bool|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function export($name = '用户信息')
    {
        //  数据太大不允许导出
        if ($this->count() > 1000)
            return '导出数据较大，请精准搜索条件后导出';
        //  获取数据源
        $this->format = [
            'avatar' => function($val) { return $val->avatar; },
            'hobby' => function($val) {
                $list = [];
                foreach (explode(',', $val->hobby) as $k => $v) {
                    if ($hobby = $this->store['hobby']->where('id', $v)->first())
                        $list[] = $hobby->title;
                }

                return $list ? implode(',', $list) : null;
            },
            'status' => function($val) { return $this->store['status'][$val->status]; },
            '_action' => ['type' => 'remove']
        ];
        $this->get();
        $list = $this->getFormat();
        //  设置表头
        $column = [
            'UID', '手机号', '昵称' => 50, '头像' => 'image', '邀请码', '已邀请人数',
            '性别', '等级', '爱好', '邀请人' => 60, '积分', '余额',
            '生日', '活跃时间' => 30, '签到日期', '登录IP', '登录时间',
            '注册日期', '更新日期', '状态', '附加',
        ];
        //  遍历数据
        $data = [];
        foreach ($list as $key => $val) {
            $data[] = [
                $val->uid, $val->phone, $val->nickname, $val->avatar, $val->code, $val->inviteCount,
                $val->sex, $val->level, $val->hobby, $val->inviteUid, $val->credit, $val->balance,
                $val->birthday, $val->activeTime, $val->signDate, $val->loginIpv4i, $val->loginTime,
                $val->createTime, $val->updateTime, $val->status, $val->data,
            ];
        }

        //  导出文件
        return (new Spreadsheet())->download($name . '.xls', ['sheet' => $name, 'column' => $column, 'height' => 50], $data);
    }
}
