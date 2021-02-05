<?php

namespace Demon\AdminLaravel\access\model;

use Illuminate\Support\Facades\Schema;

class BaseModel extends \Illuminate\Database\Eloquent\Model
{

    public function __construct()
    {
        $this->connection = config('admin.connection', 'admin');
        $this->timestamps = false;
        parent::__construct();
    }
}
