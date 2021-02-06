<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\Controller;
use Illuminate\Support\Facades\Artisan;

class AuthController extends Controller
{
    protected $loginExcept = ['login', 'locale'];

    protected $accessExcept = ['*'];

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function login()
    {
        //  页面跳转
        $url = session('url', admin_url('/'));
        $url = $url == admin_url('auth/login') ? admin_url('/') : $url;
        if (DEMON_SUBMIT) {
            //  验证参数
            $data = $this->api->arguer([
                'account' => ['name' => app('admin')->__('base.auth.account'), 'rule' => 'required|string', 'message' => app('admin')->__('base.auth.error_account')],
                'password' => ['name' => app('admin')->__('base.auth.password'), 'rule' => 'required|string', 'message' => app('admin')->__('base.auth.error_password')],
                'captcha' => ['name' => app('admin')->__('base.auth.captcha'), 'rule' => 'required|in:' . app('admin')->captcha(), 'message' => app('admin')->__('base.auth.error_captcha')]
            ]);
            //  验证登录
            $user = $this->api->check(UserModel::password('username', $data['account'], $data['password']));
            //  保存用户信息
            session(['uid' => $user->uid]);

            //  登录成功
            return $this->api->setMessage(app('admin')->__('base.auth.login_success'))->setData(['url' => $url])->send();
        }
        else {
            if ($this->uid)
                return redirect($url);

            return admin_view('preset.access.login', [
                'backgroundImage' => app('admin')->getBackgroundImage(function($config) { return '//img.infinitynewtab.com/wallpaper/' . ($config['mode'] == 'random' ? mt_rand(1, 4049) : date('Ymd') % 4049) . '.jpg'; })
            ]);
        }
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
            $this->api->check(UserModel::edit($this->uid, arguer('data', [], 'array')));

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
