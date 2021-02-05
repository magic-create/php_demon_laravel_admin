<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\Admin;
use Demon\AdminLaravel\AdminServiceProvider;
use Demon\AdminLaravel\Controller as Controllers;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Controller extends Controllers
{
    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if (DEMON_SUBMIT) {
            $data = $this->api->arguer([
                'account' => [
                    'name' => app('admin')->__('base.auth.account'),
                    'rule' => 'required|string|in:admin',
                    'message' => app('admin')->__('base.auth.error_account')
                ],
                'password' => [
                    'name' => app('admin')->__('base.auth.password'),
                    'rule' => 'required|string|size:32|in:' . bomber()->md5('demon', false),
                    'message' => app('admin')->__('base.auth.error_password')
                ],
                'captcha' => [
                    'name' => app('admin')->__('base.auth.captcha'),
                    'rule' => 'required|in:' . session('extend/image/captcha'),
                    'message' => app('admin')->__('base.auth.error_captcha')
                ]
            ]);

            return $this->api->setMessage('登录成功')->setData('url', admin_url('example'))->send();
        }
        else
            return view('admin::preset.example.login', [
                'backgroundImage' => app('admin')->getBackgroundImage(function($config) { return '//img.infinitynewtab.com/wallpaper/' . ($config['mode'] == 'random' ? mt_rand(1, 4049) : date('Ymd') % 4049) . '.jpg'; })
            ]);
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
        //  操作动作
        switch (arguer('_action')) {
            //  图表
            case 'charts':
                $pie = function() {
                    return [
                        ['name' => 'Test 1.st', 'value' => rand(1e3, 9e5)],
                        ['name' => 'Test 2.nd', 'value' => rand(1e3, 9e5)],
                        ['name' => 'Test 3.rd', 'value' => rand(1e3, 9e5)],
                        ['name' => 'Test 4.th', 'value' => rand(1e3, 9e5)]
                    ];
                };
                $line = function() {
                    $date = [];
                    for ($i = 30; $i >= 0; $i--)
                        $date[] = msdate('Y-m-d', bomber()->timeBuild("-{$i} day", 'Y-m-d'));
                    $item = ['Test 1.st', 'Test 2.nd', 'Test 3.rd', 'Test 4.th'];
                    $data = [];
                    foreach ($item as $key => $val) {
                        foreach ($date as $v)
                            $data[$key][] = rand(6e4 * (1 + $key * 0.5), 9e4 * (1 + $key * 0.5));
                    }

                    return compact('item', 'date', 'data');
                };
                $bar = function() {
                    $date = [];
                    for ($i = 7; $i >= 0; $i--)
                        $date[] = msdate('Y-m-d', bomber()->timeBuild("-{$i} day", 'Y-m-d'));
                    $item = ['Test 1.st', 'Test 2.nd', 'Test 3.rd', 'Test 4.th'];
                    $data = [];
                    foreach ($item as $key => $val) {
                        foreach ($date as $v)
                            $data[$key][] = rand(1e3, 9e4);
                    }

                    return compact('item', 'date', 'data');
                };

                return $this->api->setData(['pie' => $pie(), 'line' => $line(), 'bar' => $bar()])->send();
                break;
            default:
                //  随机统计数据
                $stat = [
                    'User' => ['icon' => 'user', 'data' => rand(1e3, 9e5), 'ratio' => bomber()->doubleRand(0, 2, 3) - 1],
                    'Order' => ['icon' => 'receipt', 'data' => rand(1e3, 9e5), 'ratio' => bomber()->doubleRand(0, 2, 3) - 1],
                    'Revenue' => ['icon' => 'calculator', 'data' => rand(1e3, 9e5), 'ratio' => bomber()->doubleRand(0, 2, 3) - 1],
                    'Product' => ['icon' => 'tags', 'data' => rand(1e3, 9e5), 'ratio' => bomber()->doubleRand(0, 2, 3) - 1],
                ];

                //  最近消息
                $message = [];
                $lastTime = DEMON_TIME;
                for ($i = rand(0, 10); $i >= 0; $i--) {
                    $lastTime -= rand(10, 60 * 60 * 3);
                    $message[] = [
                        'avatar' => '/static/admin/images/avatar/' . rand(1, 10) . '.jpg',
                        'nickname' => bomber()->rand(rand(2, 6), 'chinese'),
                        'content' => bomber()->rand(rand(16, 64), 'chinese'),
                        'time' => $lastTime
                    ];
                }

                //  活动安排
                $activity = [];
                $lastTime += 86400 * 90;
                for ($i = rand(0, 8); $i >= 0; $i--) {
                    $lastTime -= rand(86400, 86400 * 7);
                    $activity[] = [
                        'content' => bomber()->rand(rand(16, 64), 'chinese'),
                        'time' => $lastTime
                    ];
                }

                //  最新消息
                $notice = [
                    'avatar' => '/static/admin/images/avatar/' . rand(1, 10) . '.jpg',
                    'nickname' => bomber()->rand(rand(2, 6), 'chinese'),
                    'content' => bomber()->rand(rand(16, 128), 'chinese'),
                    'time' => DEMON_TIME - rand(10, 60 * 60 * 3),
                    'url' => 'javascript:'
                ];

                //  待办
                $todo = [];
                for ($i = rand(0, 8); $i >= 0; $i--) {
                    $todo[] = [
                        'title' => bomber()->rand(rand(2, 6), 'chinese'),
                        'tag' => rand(1, 9e3),
                        'url' => 'javascript:'
                    ];
                }

                return view('admin::preset.example.index', compact('stat', 'message', 'activity', 'notice', 'todo'));
        }
    }

    /**
     * 表格
     *
     * @param Table $table
     *
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function table(Table $table)
    {
        //  操作动作
        switch (arguer($table->config->actionName)) {
            //  导出
            case 'export':
                return $table->export();
                break;
            //  新增
            case 'add':
                if (DEMON_SUBMIT) {
                    $this->api->check(Service::add(arguer('data', [], 'array')));

                    return $this->api->send();
                }
                else
                    return view('admin::preset.example.table_info', ['store' => Service::fieldStore()]);
                break;
            //  积分
            case 'credit':
                $parm = $this->api->validator([
                    'uid' => ['rule' => 'required|numeric', 'message' => '请选择正确的用户'],
                    'type' => ['rule' => 'required|numeric', 'message' => '请选择正确的类型'],
                ]);
                $info = Service::find($parm['uid']);
                if (!$info)
                    abort(DEMON_CODE_PARAM);
                if (DEMON_SUBMIT) {
                    $this->api->check(Service::credit($parm['uid'], $parm['type'], arguer('change', 0, 'double')));

                    return $this->api->send();
                }
                else
                    return view('admin::preset.example.table_credit', ['info' => $info, 'type' => $parm['type'], 'credit' => Service::fieldStore('credit')]);
                break;
            //  编辑
            case 'edit':
                $uid = arguer('uid', 0, 'abs');
                if (!$uid)
                    abort(DEMON_CODE_PARAM);
                $info = Service::find($uid);
                if (!$info)
                    abort(DEMON_CODE_PARAM);
                if (DEMON_SUBMIT) {
                    $this->api->check(Service::edit($uid, arguer('data', [], 'array')));

                    return $this->api->send();
                }
                else
                    return view('admin::preset.example.table_info', ['info' => $info, 'store' => Service::fieldStore()]);
                break;
            //  信息
            case 'info':
                $uid = arguer('uid', 0, 'abs');
                if (!$uid)
                    abort(DEMON_CODE_PARAM);
                $info = Service::find($uid);
                if (!$info)
                    abort(DEMON_CODE_PARAM);

                return view('admin::preset.example.table_info', ['info' => $info, 'readonly' => true, 'store' => Service::fieldStore()]);
                break;
            //  状态
            case 'status':
                $this->api->check(Service::updateStatus(arguer('uid'), arguer('status', false, 'bool')));

                return $this->api->send();
                break;
            //  删除
            case 'delete':
                $this->api->check(Service::updateStatus(arguer('uid'), -1));

                return $this->api->send();
                break;
            //  邀请注册
            case 'invite':
                $this->api->check(Service::add(Service::randData(arguer())));

                return $this->api->send();
                break;
            //  查看受邀人
            case 'invited':
                return $table->render('admin::preset.example.table', ['layer' => true]);
                break;
            default:
                return $table->render('admin::preset.example.table');
        }
    }

    /**
     * 表单
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function form()
    {
        if (DEMON_SUBMIT) {
            $data = arguer();
            $validator = validator($data, ['api' => 'required']);
            if ($validator->fails())
                return response($validator->errors()->toArray(), DEMON_CODE_PARAM);

            return response($data);
        }

        return view('admin::preset.example.form');
    }

    /**
     * 弹出层
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function layer()
    {
        return view('admin::preset.example.layer');
    }

    /**
     * 小部件
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function widget()
    {
        return view('admin::preset.example.widget');
    }

    /**
     * 富文本编辑
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function editor()
    {
        return view('admin::preset.example.editor');
    }

    /**
     * Markdown编辑
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function markdown()
    {
        return view('admin::preset.example.markdown');
    }
}
