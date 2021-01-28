<?php

namespace App\Admin\Models;

class Model extends App\Models\Model
{
    function __construct()
    {
        $this->connection = 'admin';
        parent::__construct();
    }
}
