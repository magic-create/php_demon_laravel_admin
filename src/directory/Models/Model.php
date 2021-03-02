<?php

namespace App\Admin\Models;

class Model extends App\Models\Model
{
    function __construct()
    {
        $this->connection = config('admin.connection');
        parent::__construct();
    }
}
