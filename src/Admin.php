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

use Illuminate\Config\Repository;

class Admin
{
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('admin');
    }
}
