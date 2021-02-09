<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\Controller;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    protected $loginExcept = ['locale'];

    protected $accessExcept = ['*'];

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 登出
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function logout()
    {
        //  移除用户信息
        session(['uid' => 0]);
        //  记录标记
        app('admin')->log->setTag('auth.logout');

        //  登出成功
        return $this->api->setMessage(app('admin')->__('base.auth.logout_success'))->send();
    }

    /**
     * 变更设置
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setting()
    {
        if (DEMON_SUBMIT) {
            //  检查内容
            $change = $this->api->check(UserModel::edit($this->uid, arguer('data', [], 'array')));
            //  记录标记
            app('admin')->log->setTag('auth.setting')->setContent($change);

            return $this->api->setMessage(app('admin')->__('base.auth.setting_success'))->send();
        }
        else
            return admin_view('preset.access.setting', ['access' => app('admin')->access, 'store' => UserModel::fieldStore()]);
    }

    /**
     * 切换语言
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function locale()
    {
        //  保存设置语言
        session(['locale' => arguer('locale', app()->getLocale(), 'string')]);

        return $this->api->setMessage(app('admin')->__('base.auth.locale_success'))->send();
    }

    /**
     * 清理缓存
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function clear()
    {
        Artisan::call('view:clear');
        if (is_file(app()->getCachedConfigPath()))
            Artisan::call('config:cache');

        //  清理成功
        return $this->api->setMessage(app('admin')->__('base.auth.clear_success'))->send();
    }
}
