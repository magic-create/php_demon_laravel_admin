<?php

namespace Demon\AdminLaravel;

use Demon\AdminLaravel\access\model\UserModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var Api 接口服务
     */
    protected $api;

    /**
     * @var int 登录用户
     */
    protected $uid = 0;

    /**
     * @var null|Collection 登录用户
     */
    protected $user = null;

    /**
     *
     * @var array 无需登录
     */
    protected $loginExcept = [];

    /**
     *
     * @var array 无需权限
     */
    protected $accessExcept = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        //  接口服务
        $this->api = $this->api ? : new Api();
        //  登录用户
        $this->uid = request()->get('uid');
        //  Access
        if (config('admin.access')) {
            //  Auth
            if (get_called_class() != config('admin.authentication')) {
                $action = request()->route()->getActionMethod();
                //  Login
                if ($this->__login($action) !== true)
                    $this->__access($action);
                //  Badge
                if (config('admin.badge'))
                    app()->call([app()->make(config('admin.badge')), 'setBadge']);
            }
        }

        return 1;
    }


    /**
     * 检查登录
     *
     * @param string $action
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function __login($action)
    {
        //  检查登录
        if (config('admin.access')) {
            //  已经登录则验证用户
            if ($this->uid) {
                if ($this->user = UserModel::find($this->uid)) {
                    //  视图变量
                    view()->share('user', $this->user);
                    //  更新活跃时间
                    UserModel::where('uid', $this->uid)->update(['activeTime' => mstime()]);
                }
                //  用户无效或状态不正确
                if (!$this->user || $this->user->status < 1) {
                    $this->uid = 0;
                    $this->user = null;
                }
            }
            //  不需要登录
            if (in_array('*', $this->loginExcept) || in_array($action, $this->loginExcept))
                return true;
            //  需要登录
            if (!$this->uid) {
                abort(DEMON_SUBMIT ?
                    response()->json(['code' => DEMON_CODE_AUTH, 'message' => admin_error(DEMON_CODE_AUTH)], DEMON_CODE_AUTH) :
                    response(app()->make(config('admin.authentication'))->login()));
            }
        }
    }

    /**
     * 检查权限
     *
     * @param string $action
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function __access($action)
    {
        //  检查权限
        if (config('admin.access')) {
            //  需要权限
            if (!in_array('*', $this->accessExcept) && !in_array($action, $this->accessExcept) && !app('admin')->access->check(url()->current())) {
                abort(DEMON_SUBMIT ?
                    response()->json(['code' => DEMON_CODE_ACCESS, 'message' => admin_error(DEMON_CODE_ACCESS)], DEMON_CODE_ACCESS) :
                    response()->view('admin::preset.error.general', ['code' => DEMON_CODE_ACCESS, 'message' => admin_error(DEMON_CODE_ACCESS)], DEMON_CODE_ACCESS));
            }
        }
    }
}
