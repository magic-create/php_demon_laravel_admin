<?php

namespace Demon\AdminLaravel\access\model;

use Illuminate\Support\Facades\DB;

class LogModel extends BaseModel
{
    public function __construct()
    {
        $this->table = 'admin_log';
        $this->primaryKey = 'lid';
        parent::__construct();
    }

    public static function findAndFormat($lid)
    {
        return self::from((new LogModel())->getTable(), 'a')
                   ->leftJoin((new UserModel())->getTable() . ' as b', 'b.uid', '=', 'a.uid')
                   ->where('a.lid', $lid)
                   ->selectRaw('a.*,INET_NTOA(a.ip) as ip,b.username,b.remark as userRemark')
                   ->first();
    }

    public static function del($lid)
    {
        self::whereIn('lid', is_array($lid) ? $lid : [$lid])->delete();

        return true;
    }

    public static function clear()
    {
        self::truncate();

        return true;
    }
}
