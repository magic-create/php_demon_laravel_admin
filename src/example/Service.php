<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\access\model\LogModel;
use Demon\AdminLaravel\Api;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Service
{
    /**
     *  数据库连接
     */
    static $connection = 'admin';
    /**
     *  主表
     */
    const MasterModel = 'example_master';
    /**
     *  主键
     */
    const MasterKey = 'uid';
    /**
     *  从表
     */
    const SlaveModel = 'example_slave';

    /**
     *  从表主键
     */
    const SlaveKey = 'id';

    /**
     *  配置表
     */
    const SettingModel = 'example_setting';

    /**
     *  配置表主键
     */
    const SettingKey = 'id';

    /**
     * 邀请码编排
     */
    const InviteCodeKey = 'WHFBLUMYIAQNGDZOTRXPSEVKCJ';

    /**
     * 邀请码偏移量
     */
    const InviteCodeOffset = 1e5;

    /**
     * 受保护的字段
     */
    const ProtectedField = ['uid', 'code', 'inviteUid', 'createTime'];

    /**
     * Service constructor.
     */
    function __construct()
    {
        self::$connection = config('admin.connection', 'admin');
    }

    /**
     * 字段规则
     *
     * @param string $field
     *
     * @return array|mixed|null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function fieldStore($field = '')
    {
        //  设置内容
        $store = [
            'credit' => [1 => ['name' => '积分', 'alias' => 'credit', 'decimals' => 0], 2 => ['name' => '余额', 'alias' => 'balance', 'decimals' => 2]],
            'sex' => [-1 => '女', 0 => '其他', 1 => '男'],
            'avatar' => ['none' => '未设置', 'have' => '已设置'],
            'status' => [0 => '隐藏', 1 => '正常'],
            'hobby' => collect(array_to_object([['id' => 1, 'title' => '运动'], ['id' => 2, 'title' => '娱乐'], ['id' => 3, 'title' => '收藏'], ['id' => 4, 'title' => '乐器'], ['id' => 5, 'title' => '文艺'], ['id' => 6, 'title' => '社交']])),
        ];
        for ($i = 0; $i <= 100; $i++)
            $store['level'][$i] = array_to_object(['name' => 'LV.' . bomber()->strFill(3, $i)]);

        return $field ? ($store[$field] ?? null) : $store;
    }

    /**
     * 获取字段
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function fieldList()
    {
        return Schema::connection(self::$connection)->getColumnListing(self::MasterModel);
    }

    /**
     * 构造模型
     *
     * @param string $table
     *
     * @return \Illuminate\Database\ConnectionInterface|\Illuminate\Database\Query\Builder
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function model(string $table = '')
    {
        return $table ? DB::connection(self::$connection)->table($table) : DB::connection(self::$connection);
    }

    /**
     * 更新状态
     *
     * @param $uid
     * @param $status
     *
     * @return bool|int
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function updateStatus($uid, $status)
    {
        //  检查条件
        if (!$uid || !in_array($status, array_keys(self::fieldStore('status'))))
            return DEMON_CODE_PARAM;

        //  返回执行结果
        return DB::connection(self::$connection)->table(self::MasterModel)->whereIn(self::MasterKey, is_array($uid) ? $uid : explode(',', $uid))->update(['status' => (int)$status, 'updateTime' => mstime()]) ? true : DEMON_CODE_COND;
    }

    /**
     * 获取
     *
     * @param $uid
     *
     * @return \Illuminate\Support\Collection|null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function find($uid)
    {
        if (!$uid)
            return null;
        $field = ['a.*', 'b.nickname as inviteNickname'];
        $query = DB::connection(self::$connection)
                   ->table(self::MasterModel, 'a')
                   ->leftJoin(self::MasterModel . ' as b', 'b.uid', '=', 'a.inviteUid')
                   ->where('a.uid', $uid);
        $credit = self::fieldStore('credit');
        foreach ($credit as $type => $v) {
            $alias = 'c_' . $type;
            $field[] = "{$alias}.value as {$v['alias']}";
            $query->leftJoin(Service::SlaveModel . ' as ' . $alias, function($query) use ($alias, $type) {
                $query->on($alias . '.uid', '=', 'a.uid')->where($alias . '.type', $type);
            });
        }
        $info = $query->first($field);
        if ($info) {
            $info->avatar = $info->avatar ? : bomber()->strImage($info->nickname, 'svg', ['calc' => true, 'substr' => true]);
            $info->hobby = explode(',', $info->hobby);
            foreach ($credit as $type => $v)
                $info->{$v['alias']} = bomber()->doublePrecision($info->{$v['alias']}, $v['decimals']);
        }

        return $info;
    }

    /**
     * 检查
     *
     * @param array $data
     *
     * @return array|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function checkData($data = [])
    {
        //  字段属性
        $store = self::fieldStore();
        $data['intro'] = arguer('intro', null, 'xss', $data);
        $reData = (new Api())->validator([
            'nickname' => ['rule' => 'required|string|min:2|max:16', 'message' => '请输入2至16个字的用户昵称'],
            'level' => ['rule' => 'required|numeric|in:' . implode(',', array_keys($store['level'])), 'message' => '请选择正确的等级'],
            'sex' => ['rule' => 'required|numeric|in:' . implode(',', array_keys($store['sex'])), 'message' => '请选择正确的性别'],
            'birthday' => ['rule' => 'required|date', 'message' => '请输入正确的生日'],
            'intro' => ['rule' => 'required|string', 'message' => '请输入正确的简介'],
        ], [], [], $data);
        if (!error_check($reData))
            return $reData;
        //  Sex为enum类型需要特殊处理
        $reData['sex'] = (string)$reData['sex'];
        //  头像为Null
        $reData['avatar'] = ($reData['avatar'] ?? null) ? : null;
        //  验证字段
        if (($reData['phone'] ?? null) && !bomber()->regexp($reData['phone'], 'mobile'))
            return error_build('手机号码格式有误');
        //  验证邀请人
        if ($reData['inviteCode'] ?? null) {
            $reData['inviteUid'] = bomber()->inviteCode($reData['inviteCode'], 0, self::InviteCodeKey, self::InviteCodeOffset);
            if ($reData['inviteUid'] < 1 || !self::find($reData['inviteUid']))
                return error_build('邀请码错误');
        }
        //  过滤字段
        if (isset($reData['hobby'])) {
            $hobbys = bomber()->objectKeys(bomber()->arrayIndex($store['hobby'], 'id'));
            foreach ($reData['hobby'] as $key => $val) {
                if (!in_array($val, $hobbys))
                    unset($reData['hobby'][$key]);
            }
            $reData['hobby'] = implode(',', $reData['hobby'] ?? []) ? : null;
        }
        //  过滤无效字段
        $field = self::fieldList();
        foreach ($reData as $key => $val) {
            if (!in_array($key, $field))
                unset($reData[$key]);
        }

        return $reData;
    }

    /**
     * 随机生成数据
     *
     * @param array $preData
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function randData($preData = [])
    {
        $store = self::fieldStore();
        $hobby = array_filter(Service::fieldStore('hobby')->map(function($val) { return rand(0, 100) >= 80 ? $val->id : null; })->toArray());
        shuffle($hobby);
        $data = $preData + [
                'nickname' => $preData['nickname'] ?? bomber()->rand(rand(2, 16), 'chinese'),
                'sex' => $preData['sex'] ?? array_rand(array_flip(array_keys($store['sex']))),
                'inviteCode' => $preData['inviteCode'] ?? (DB::connection(self::$connection)->table(self::MasterModel)->orderByRaw('RAND()')->first()->code ?? null),
                'level' => $preData['level'] ?? array_rand(array_flip(array_keys($store['level']))),
                'birthday' => $preData['birthday'] ?? date('Y-m-d', rand(strtotime('1900-01-01'), strtotime('Ymd'))),
                'intro' => $preData['intro'] ?? admin_html('fast', bomber()->rand(rand(16, 128), 'chinese'), [], 'p'),
                'hobby' => $preData['hobby'] ?? $hobby,
            ];

        return $data;
    }

    /**
     * 新增
     *
     * @param array $data
     *
     * @return array|bool|int|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function add($data = [])
    {
        $reData = self::checkData($data);
        if (!error_check($reData))
            return $reData;
        $reData += ['createTime' => mstime()];

        //  返回执行结果
        $uid = DB::connection(self::$connection)->table(self::MasterModel)->insertGetId($reData);
        if ($uid) {
            DB::connection(self::$connection)->table(self::MasterModel)->where('uid', $uid)->update(['code' => bomber()->inviteCode($uid, 1, self::InviteCodeKey, self::InviteCodeOffset)]);

            return true;
        }
        else
            return DEMON_CODE_DATA;
    }

    /**
     * 编辑
     *
     * @param       $uid
     * @param array $data
     *
     * @return array|bool|int|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function edit($uid, $data = [])
    {
        //  获取当前信息
        if (!$uid)
            return DEMON_CODE_PARAM;
        $info = self::find($uid);
        if (!$info)
            return DEMON_CODE_PARAM;
        $reData = self::checkData($data);
        if (!error_check($reData))
            return $reData;
        //  移除保护字段
        bomber()->objectFilter($reData, self::ProtectedField, -1);
        $reData += ['updateTime' => mstime()];
        //  相同内容过滤
        foreach ($reData as $key => $val) {
            if ($val == $info->{$key})
                unset($reData[$key]);
        }

        //  返回执行结果
        return DB::connection(self::$connection)->table(self::MasterModel)->where('uid', $uid)->update($reData) ? true : DEMON_CODE_DATA;
    }

    /**
     * 变更积分
     *
     * @param $uid
     * @param $type
     * @param $change
     *
     * @return bool|int|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function credit($uid, $type, $change)
    {
        //  无变化
        if (!$change)
            return error_build('无变化');
        //  积分信息
        $credit = self::fieldStore('credit')[$type] ?? null;
        if (!$credit)
            return error_build('积分类型错误');
        //  获取当前数据，如果没有则创建
        $now = DB::connection(self::$connection)->table(self::SlaveModel)->where(['uid' => $uid, 'type' => $type])->first();
        if (!$now) {
            $foo = DB::connection(self::$connection)->table(self::SlaveModel)->insertGetId(['uid' => $uid, 'type' => $type, 'createTime' => mstime()]);
            if (!$foo)
                return DEMON_CODE_DATA;

            return self::credit($uid, $type, $change);
        }
        //  构造器
        $query = DB::connection(self::$connection)->table(self::SlaveModel)->where('id', $now->id);
        //  变更数量绝对值
        $num = bcadd(0, abs($change), $credit['decimals']);
        //  扣除
        if ($change < 0) {
            //  如果是减少则判断是否足够
            if ($now->value < $num)
                return error_build($credit['name'] . '数量不足');

            return $query->where('value', '>=', $num)->update(['updateTime' => mstime(), 'value' => DB::raw("value-{$num}")]) ? true : DEMON_CODE_DATA;
        }
        //  增加
        else
            return $query->update(['updateTime' => mstime(), 'value' => DB::raw("value+{$num}")]) ? true : DEMON_CODE_DATA;
    }

    /**
     * 获取配置信息
     *
     * @param null $module
     *
     * @return \Illuminate\Support\Collection
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public static function setting($module = null)
    {
        if ($module)
            return DB::connection(self::$connection)->table(self::SettingModel)->whereIn(is_array($module) ? $module : [$module])->get();
        else
            return DB::connection(self::$connection)->table(self::SettingModel)->get();
    }

    /**
     * 菜单统计
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setBadge($uid)
    {
        app('admin')->setBadge(['example/table' => [rand(0, 10), '#ff0000'], 'example/form' => rand(0, 10)]);
    }

    /**
     * 设置通知内容
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setNotification($uid)
    {
        rand(0, 2) ?
            app('admin')->setNotification([
                ['theme' => rand(0, 1) ? 'danger' : 'warning', 'icon' => 'fa fa-house-damage', 'title' => '__base.access.batch', 'content' => ['__base.access.batch_confirm', ['action' => 'Test', 'length' => rand(1, 99)]]],
                ['theme' => rand(0, 1) ? 'info' : 'primary', 'title' => '__base.access.batch', 'content' => ['__base.access.batch_confirm', ['action' => 'Test', 'length' => rand(1, 99)]]],
                ['icon' => 'fa fa-cloud-sun-rain', 'title' => '__base.access.batch', 'content' => ['__base.access.batch_confirm', ['action' => 'Test', 'length' => rand(1, 99)]]],
            ]) : null;
    }

    /**
     * 保存日志
     *
     * @param $response
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function saveLog($response)
    {
        if (DEMON_SUBMIT && !app('admin')->log->break)
            LogModel::insert(app('admin')->log->build(function(&$data) {
                $data['arguments'] = app('admin')->log->filter($data['arguments']);
            }));
    }
}
