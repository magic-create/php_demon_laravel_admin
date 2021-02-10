<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\Controller;
use Illuminate\Support\Facades\Artisan;

class AuthController extends Controller
{
    protected $loginExcept = ['*'];

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
        $url = session('admin.login', admin_url());
        $url = $url == admin_url('auth/login') ? admin_url() : $url;
        $url = admin_tabs('html', $url, 'frame');
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
            session(['uid' => $user->uid, 'admin.login' => null]);
            //  记录标记
            app('admin')->log->setUid($user->uid)->setTag('auth.login');

            //  登录成功
            return $this->api->setMessage(app('admin')->__('base.auth.login_success'))->setData(['url' => $url])->send();
        }
        else {
            if ($this->uid)
                return redirect($url);

            return admin_view('preset.access.login', [
                'url' => $url,
                'backgroundImage' => app('admin')->getBackgroundImage(function($config) { return '//img.infinitynewtab.com/wallpaper/' . ($config['mode'] == 'random' ? mt_rand(1, 4049) : date('Ymd') % 4049) . '.jpg'; })
            ]);
        }
    }
}
