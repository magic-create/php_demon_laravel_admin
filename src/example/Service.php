<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Service
{
    /**
     *  Mysql连接
     */
    const connection = 'admin';
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
     *  主键
     */
    const SlaveKey = 'id';

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
            'status' => [-1 => '已删', 0 => '隐藏', 1 => '正常'],
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
        return Schema::connection(self::connection)->getColumnListing(self::MasterModel);
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
        return DB::connection(self::connection)->table(self::MasterModel)->whereIn(self::MasterKey, is_array($uid) ? $uid : explode(',', $uid))->update(['status' => (int)$status, 'updateTime' => mstime()]) ? true : DEMON_CODE_COND;
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
        $field = ['a.*'];
        $query = DB::connection(self::connection)
                   ->table(self::MasterModel, 'a')
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
        //  验证字段
        if (($reData['phone'] ?? null) && !bomber()->regexp($reData['phone'], 'mobile'))
            return error_build('手机号码格式有误');
        //  过滤字段
        if ($reData['hobby'] ?? []) {
            $hobbys = bomber()->objectKeys(bomber()->arrayIndex($store['hobby'], 'id'));
            foreach ($reData['hobby'] as $key => $val) {
                if (!in_array($val, $hobbys))
                    unset($reData['hobby'][$key]);
            }
            $reData['hobby'] = implode(',', $reData['hobby'] ?? []);
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
        return DB::connection(self::connection)->table(self::MasterModel)->insertGetId($reData) ? true : DEMON_CODE_DATA;
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
        $reData += ['updateTime' => mstime()];
        //  相同内容过滤
        foreach ($reData as $key => $val) {
            if ($val == $info->{$key})
                unset($reData[$key]);
        }

        //  返回执行结果
        return DB::connection(self::connection)->table(self::MasterModel)->where('uid', $uid)->update($reData) ? true : DEMON_CODE_DATA;
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
        $now = DB::connection(self::connection)->table(self::SlaveModel)->where(['uid' => $uid, 'type' => $type])->first();
        if (!$now) {
            $foo = DB::connection(self::connection)->table(self::SlaveModel)->insertGetId(['uid' => $uid, 'type' => $type, 'createTime' => mstime()]);
            if (!$foo)
                return DEMON_CODE_DATA;

            return self::credit($uid, $type, $change);
        }
        //  构造器
        $query = DB::connection(self::connection)->table(self::SlaveModel)->where('id', $now->id);
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
}
