<?php

namespace Demon\AdminLaravel;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Api 接口服务
     */
    protected $api;

    public function __construct()
    {
        //  接口服务
        $this->api = $this->api ? : new Api();
    }
}
