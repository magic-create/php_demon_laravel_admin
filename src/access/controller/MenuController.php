<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\MenuModel;
use Demon\AdminLaravel\access\table\MenuTable;
use Demon\AdminLaravel\Controller;

class MenuController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(MenuTable $table)
    {
        return $table->render('preset.access.menu', ['access' => app('admin')->access]);
    }

    public function weight()
    {
        $this->api->check(MenuModel::updateWeight(arguer('mid'), arguer('referId')));

        return $this->api->send();
    }

    public function add()
    {
        if (DEMON_SUBMIT) {
            $this->api->check(MenuModel::add(arguer('data', [], 'array')));

            return $this->api->send();
        }
        else
            return admin_view('preset.access.menu_info', ['access' => app('admin')->access, 'store' => MenuModel::fieldStore()]);
    }

    public function edit()
    {
        $info = MenuModel::find(arguer('mid', 0, 'abs'));
        if (!$info)
            abort(DEMON_CODE_PARAM);
        if (DEMON_SUBMIT) {
            $data = arguer('data', [], 'array');
            $this->api->check(MenuModel::edit($info->mid, $data));
            //  设置记录
            $this->log->setTag('admin.access.menu')->setContent(bomber()->arrayDiffer($data, $info));

            return $this->api->send();
        }
        else
            return admin_view('preset.access.menu_info', ['info' => $info, 'access' => app('admin')->access, 'store' => MenuModel::fieldStore()]);
    }

    public function status()
    {
        $this->api->check(MenuModel::updateStatus(arguer('mid'), arguer('status', false, 'bool')));

        return $this->api->send();
    }

    public function del()
    {
        $this->api->check(MenuModel::del(arguer('mid')));

        return $this->api->send();
    }
}
