<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\Controller;

class AuthController extends Controller
{
    protected $loginExcept = ['*'];

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
        $url = session('url');
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

            return view('admin::preset.access.login', [
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
}
