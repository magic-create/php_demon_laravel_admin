<?php

namespace Demon\AdminLaravel\access\controller;

use Demon\AdminLaravel\access\model\LogModel;
use Demon\AdminLaravel\access\table\LogTable;
use Demon\AdminLaravel\Controller;

class LogController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(LogTable $table)
    {
        switch (arguer($table->config->actionName)) {
            case 'export':
                return $table->export();
                break;
            default:
                return $table->render('preset.access.log', ['access' => app('admin')->access]);
                break;
        }
    }

    public function info()
    {
        $info = LogModel::findAndFormat(arguer('lid', 0, 'abs'));
        if (!$info)
            abort(DEMON_CODE_PARAM);

        return admin_view('preset.access.log_info', ['info' => $info, 'access' => app('admin')->access]);
    }

    public function del()
    {
        app('admin')->log->setBreak(true);
        $this->api->check(LogModel::del(arguer('lid')));

        return $this->api->send();
    }

    public function clear()
    {
        $this->api->check(LogModel::clear());

        return $this->api->send();
    }
}
