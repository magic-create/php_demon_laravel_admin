<?php

namespace Demon\AdminLaravel\example;

use Illuminate\Support\Facades\DB;

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
     * fieldStore
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
        ];
        for ($i = 0; $i <= 100; $i++)
            $store['level'][$i] = array_to_object(['name' => bomber()->strFill(3, $i)]);

        return $field ? ($store[$field] ?? null) : $store;
    }

    /**
     * updateStatus
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
}
