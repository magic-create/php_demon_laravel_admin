<?php

namespace Demon\AdminLaravel\example;

use App\Admin\Tables\Central\MemberTable;
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
     * @return mixed
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
