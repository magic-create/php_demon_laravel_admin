<?php

namespace App\Http\Controllers\Example;

use App\Http\Tables\Table;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * 表格测试
	 *
     * @return mixed
	 *
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function index(Table $table)
    {
        //  操作动作
        switch (arguer($table->config->actionName)) {
            //  导出
            case 'export':
                return $table->export();
                break;
            default:
                return $table->render('view');
        }
    }
}
