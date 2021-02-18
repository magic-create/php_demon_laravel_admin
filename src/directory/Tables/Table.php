<?php

namespace App\Admin\Tables;

use Demon\AdminLaravel\access\Service;
use Demon\AdminLaravel\DBTable;

class Table extends DBTable
{
    /**
     * @var Service
     */
    public $access;

    public function __construct()
    {
        $this->access = $this->access ? : app('admin')->access;
        parent::__construct();
    }
}
