<?php
/**
 * 本文件用于定义一些运维后台相关的内容
 * Created by M-Create.Team
 * Copyright 魔网天创信息科技
 * User: ComingDemon
 * Date: 2020/12/19
 * Time: 23:15
 */

namespace Demon\AdminLaravel;

use Exception;
use Illuminate\Config\Repository;

class Admin
{
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('admin');
    }

    /**
     * 获取背景图片
     *
     * @param null $call
     *
     * @return mixed|null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getBackgroundImage($call = null)
    {
        //  获取配置
        $config = $this->config['background'];
        $mode = $config['mode'];
        $list = $config['list'];
        //  未配置任何内容
        if (!$call && !$list)
            return null;
        //  获取内容
        try {
            //  指定方法
            if ($call && bomber()->isFunction($call))
                return call_user_func($call, $config);
            //  从数组中获取
            if ($list)
                return $list[$config['mode'] == 'random' ? mt_rand(0, count($list) - 1) : date('Ymd') % count($list)];

            //  都没有值
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
