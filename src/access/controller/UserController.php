<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\UserModel;
use Demon\AdminLaravel\access\table\UserTable;
use Demon\AdminLaravel\Controller;

class UserController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(UserTable $table)
    {
        return $table->render('preset.access.user', ['access' => app('admin')->access]);
    }

    public function add()
    {
        if (DEMON_SUBMIT) {
            $this->api->check(UserModel::add(arguer('data', [], 'array')));

            return $this->api->send();
        }
        else
            return admin_view('preset.access.user_info', ['access' => app('admin')->access, 'action' => __FUNCTION__, 'store' => UserModel::fieldStore()]);
    }

    public function info()
    {
        $info = UserModel::findAndRids(arguer('uid', 0, 'abs'));
        if (!$info)
            abort(DEMON_CODE_PARAM);

        return admin_view('preset.access.user_info', ['info' => $info, 'readonly' => true, 'access' => app('admin')->access, 'action' => __FUNCTION__, 'store' => UserModel::fieldStore()]);
    }

    public function edit()
    {
        $info = UserModel::findAndRids(arguer('uid', 0, 'abs'));
        if (!$info)
            abort(DEMON_CODE_PARAM);
        if (DEMON_SUBMIT) {
            $data = arguer('data', [], 'array');
            $this->api->check(UserModel::edit($info->uid, $data));
            //  设置记录
            $this->log->setTag('admin.access.user')->setContent(bomber()->arrayDiffer($data, $info));

            return $this->api->send();
        }
        else
            return admin_view('preset.access.user_info', ['info' => $info, 'access' => app('admin')->access, 'action' => __FUNCTION__, 'store' => UserModel::fieldStore()]);
    }

    public function status()
    {
        $this->api->check(UserModel::updateStatus(arguer('uid'), arguer('status', false, 'bool')));

        return $this->api->send();
    }

    public function del()
    {
        $this->api->check(UserModel::updateStatus(arguer('uid'), -1));

        return $this->api->send();
    }
}
