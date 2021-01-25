<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\Controller as Controllers;

class Controller extends Controllers
{
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
        return view('admin::preset.example.index');
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
