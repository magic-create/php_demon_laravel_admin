<?php

namespace App\Admin\Controllers\Common;

use App\Admin\Controllers\Controller;

class IndexController extends Controller
{
    protected $accessExcept = ['index'];

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     *
     * @return mixed
     *
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function index()
    {
        return admin_view('index');
    }
}
