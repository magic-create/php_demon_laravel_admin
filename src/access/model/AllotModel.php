<?php

namespace Demon\AdminLaravel\access\model;

class AllotModel extends BaseModel
{
    public function __construct()
    {
        $this->table = 'admin_allot';
        parent::__construct();
    }

    public static function updateList($uid, $rids = [])
    {
        $now = self::where('uid', $uid)->get('rid')->pluck('rid')->toArray();
        if ($add = array_diff($rids, $now)) {
            foreach ($add as $key => $rid)
                $add[$key] = ['uid' => $uid, 'rid' => $rid, 'createTime' => mstime()];
            $add = self::insert($add);
        }

        if ($del = array_diff($now, $rids))
            $del = self::where('uid', $uid)->whereIn('rid', array_values($del))->delete();

        return $add || $del ? true : DEMON_CODE_DATA;
    }
}
