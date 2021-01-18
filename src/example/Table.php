<?php

namespace Demon\AdminLaravel\example;

use App\Admin\Tables\Table as Tables;
use App\Models\Central\MemberModel;
use App\Redis\Central\LevelRedis;
use App\Redis\Central\VipRedis;
use App\Services\Central\ConfigService;
use Demon\AdminLaravel\Spreadsheet;
use Illuminate\Support\Facades\DB;

class Table extends Tables
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
        return ['batch' => true, 'key' => Service::MasterKey, 'reorder' => ['level', 'desc']];
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
        foreach ($this->store['credit'] as $type => $v) {
            $alias = 'c_' . $type;
            $credit[] = ['data' => 'c_' . $type . '.value', 'name' => $v['alias'], 'title' => $v['name'], 'where' => 'range'];
        }

        return array_merge([
            ['data' => 'a.uid', 'title' => 'UID', 'placeholder' => '精准UID，多个可用 , 分隔', 'where' => 'in'],
            ['data' => 'a.phone', 'title' => '手机号', 'where' => 'like'],
            ['data' => 'a.nickname', 'title' => '昵称', 'where' => 'like', 'type' => 'nickname', 'value' => '测'],
            ['data' => 'a.sex', 'title' => '性别', 'type' => 'select', 'option' => ['list' => $this->store['sex']]],
            [
                'data' => 'a.avatar', 'title' => '头像', 'type' => 'select', 'option' => ['list' => $this->store['avatar']],
                'where' => function($field, $value) {
                    return $value == 'have' ?
                        function($query) use ($field) { $query->whereNotNull($field)->whereOr($field, '!=', ''); } :
                        function($query) use ($field) { $query->whereNull($field)->whereOr($field, ''); };
                }
            ],
            ['data' => 'a.level', 'title' => '等级', 'type' => 'select', 'where' => 'range', 'option' => ['bind' => 'name', 'list' => $this->store['level']], 'attr' => ['data-select' => true]],
            ['data' => 'a.birthday', 'title' => '生日', 'type' => 'time', 'where' => 'range', 'attr' => ['data-time' => 'YYYY-MM-DD']],
            ['data' => 'a.activeTime', 'title' => '活跃时间', 'type' => 'time', 'where' => 'range', 'attr' => ['data-time' => null]],
            ['data' => 'a.signDate', 'title' => '签到日期', 'type' => 'time', 'where' => 'range', 'format' => function($value) { return date('Ymd', strtotime($value)); }, 'attr' => ['data-time' => 'YYYY-MM-DD']],
            ['data' => 'a.loginTime', 'title' => '登录时间', 'type' => 'time', 'where' => 'range', 'format' => 'time', 'attr' => ['data-time' => null]],
            ['data' => 'a.loginIpv4s', 'title' => '登录IP', 'where' => '%like'],
            ['data' => 'a.loginIpv4i', 'name' => 'loginIpv4', 'title' => '登录IP', 'type' => 'text', 'where' => 'between', 'format' => 'ip2long'],
            ['data' => 'a.createTime', 'title' => '注册时间', 'type' => 'time', 'where' => 'range', 'format' => 'mstime', 'attr' => ['data-time' => null]],
        ], $credit);
    }

    /**
     * 设置排序
     *
     * @return array|string[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setOrder()
    {
        return ['a.uid' => 'desc'];
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
        foreach ($this->store['credit'] as $type => $v) {
            $alias = 'c_' . $type;
            $credit[] = ['data' => $v['alias'], 'origin' => 'c_' . $type . '.value', 'title' => $v['name'], 'reorder' => true];
        }

        return array_merge([
            ['data' => 'a.uid', 'title' => 'UID', 'reorder' => true],
            ['data' => 'phone', 'title' => '手机号'],
            ['data' => 'nickname', 'title' => '昵称', 'width' => '10%', 'search' => true],
            ['data' => 'avatar', 'title' => '头像', 'width' => '60px'],
            ['data' => 'sex', 'title' => '性别'],
            ['data' => 'level', 'title' => '等级', 'reorder' => true]
        ], $credit, [
            ['data' => 'a.birthday', 'title' => '生日', 'reorder' => true],
            ['data' => 'a.activeTime', 'title' => '活跃时间', 'reorder' => true],
            ['data' => 'a.signDate', 'title' => '签到日期', 'reorder' => true],
            ['data' => 'a.loginTime', 'title' => '登录时间', 'reorder' => true],
            ['data' => 'a.createTime', 'title' => '注册日期', 'reorder' => true],
            ['data' => 'a.updateTime', 'title' => '更新日期', 'reorder' => true],
            ['data' => 'a.status', 'title' => '状态', 'action' => true, 'reorder' => true],
            ['data' => '_action', 'title' => '操作', 'action' => true],
        ]);
    }

    /**
     * 设置字段
     *
     * @return array|string[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setField()
    {
        return ['a.data'];
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
        $query = DB::connection(Service::connection)->table(Service::MasterModel . ' as a')->where('a.status', '>=', 0);
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
            $credit[$v['alias']] = function($val) use ($v) { return bomber()->doublePrecision($val->{$v['alias']}, $v['decimals']); };

        return [
                'sex' => function($val) { return $this->store['sex'][$val->sex]; },
                'level' => function($val) { return $this->store['level'][$val->level]->name ?? '未知'; },
                'signDate' => function($val) { return $val->signDate ? date('Y-m-d', strtotime($val->signDate)) : null; },
                'loginTime' => function($val) { return $val->loginTime ? date('Y-m-d H:i:s', strtotime($val->loginTime)) : null; },
                'createTime' => function($val) { return $val->createTime ? msdate('Y-m-d', $val->createTime) : null; },
                'updateTime' => function($val) { return $val->updateTime ? msdate('Y-m-d', $val->updateTime) : null; },
                'status' => function($val) { return admin_html('switch', ['action' => 'status'], $val->status); },
                '_action' => [
                    'type' => 'add',
                    'callback' => function() {
                        return admin_button('add', 'add', ['text' => '测试按钮']) .
                            admin_button('edit', 'edit') .
                            admin_button('del', 'del', ['title' => '测试按钮']) .
                            admin_button('get', 'get') .
                            admin_button('test');
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
            'status' => function($val) { return $this->store['status'][$val->status]; },
            '_action' => ['type' => 'remove']
        ];
        $list = $this->getFormat($this->get());
        //  设置表头
        $column = [
            'UID', '手机号', '昵称', '头像' => [30, 'image'],
            '性别', '等级', '积分', '余额',
            '生日', '活跃时间' => 30, '签到日期', '登录时间',
            '注册日期', '更新日期', '状态', '附加',
        ];
        //  遍历数据
        $data = [];
        foreach ($list as $key => $val)
            $data[] = array_values(object_to_array($val));

        //  导出文件
        return (new Spreadsheet())->download($name . '.xls', ['sheet' => $name, 'column' => $column, 'height' => 50], $data);
    }
}
