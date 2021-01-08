<?php

namespace App\Http\Controllers\Example;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * 弹出层测试
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function layer()
    {
        return view('view');
    }
}
