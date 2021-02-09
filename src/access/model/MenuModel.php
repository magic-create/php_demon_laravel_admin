<?php

namespace Demon\AdminLaravel\access\model;

use Demon\AdminLaravel\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuModel extends BaseModel
{
    public function __construct()
    {
        $this->table = 'admin_menu';
        $this->primaryKey = 'mid';
        parent::__construct();
    }

    public static function fieldStore($field = '')
    {
        $store = [
            'type' => ['menu' => app('admin')->access->getLang('type_menu'), 'page' => app('admin')->access->getLang('type_page'), 'action' => app('admin')->access->getLang('type_action')],
            'status' => [0 => app('admin')->access->getLang('status_0'), 1 => app('admin')->access->getLang('status_1')],
            'parent' => app('admin')->access->getAccessTreeDeep(self::get())
        ];

        return $field ? ($store[$field] ?? null) : $store;
    }

    public static function fieldList()
    {
        $model = new self();

        return Schema::connection($model->getConnectionName())->getColumnListing($model->getTable());
    }

    public static function updateStatus($mid, $status)
    {
        if (!$mid)
            return DEMON_CODE_PARAM;

        return self::where('system', 0)->whereIn('mid', is_array($mid) ? $mid : explode(',', $mid))->update(['status' => (int)$status, 'updateTime' => mstime()]) ? true : DEMON_CODE_COND;
    }

    public static function updateWeight($mid, $referId)
    {
        $store = self::fieldStore();
        $info = self::find($mid);
        if (!$info)
            return DEMON_CODE_DATA;
        $referInfo = self::find($referId);
        if (!$referInfo)
            return DEMON_CODE_DATA;
        if ($info->upId != $referInfo->upId) {
            $deep = app('admin')->access->getAccessDeep($store['parent']);
            $info = $deep->where('mid', $mid)->first();
            if ($info) {
                $parent = app('admin')->access->getAccessParents($store['parent'], $referId);
                foreach ($parent as $item) {
                    $foo = $deep->where('mid', $item)->first();
                    if ($foo && $foo->deep == $info->deep)
                        self::where('mid', $mid)->update(['weight' => $foo->weight >= $info->weight ? $foo->weight + 1 : $foo->weight - 1, 'updateTime' => mstime()]);
                }
            }
        }
        else
            self::where('mid', $mid)->update(['weight' => $referInfo->weight >= $info->weight ? $referInfo->weight + 1 : $referInfo->weight - 1, 'updateTime' => mstime()]);

        return true;
    }

    public static function checkData($data = [], $info = [])
    {
        $store = self::fieldStore();
        $reData = (new Api())->validator([
            'type' => ['rule' => 'required|string|in:' . implode(',', array_keys($store['type'])), 'name' => app('admin')->access->getLang('type')],
        ], [], [], $data);
        if (!error_check($reData))
            return $reData;
        $reData['title'] = ($reData['title'] ?? '') ? : null;
        $reData['path'] = ($reData['path'] ?? '') ? : null;
        $reData['remark'] = ($reData['remark'] ?? '') ? : null;
        $reData['upId'] = ($reData['upId'] ?? '') ? : 0;
        $reData['weight'] = ($reData['weight'] ?? '') ? : 0;
        if ($reData['upId']) {
            if ($info) {
                $childList = collect(app('admin')->access->getAccessChilds($store['parent'], $info['mid']))->pluck('mid')->toArray();
                if ($info['mid'] == $reData['upId'] || in_array($reData['upId'], $childList))
                    return error_build(DEMON_CODE_PARAM, app('admin')->access->getLang('error_parent_trace'));
            }
            $upInfo = $store['parent']->where('mid', $reData['upId'])->first();
            if (!$upInfo || $upInfo->type == 'action')
                return error_build(DEMON_CODE_PARAM, app('admin')->access->getLang('error_parent'));
            $refer = $store['parent']->where('upId', $reData['upId'])->sortBy('weight')->first();
        }
        if ($reData['path']) {
            $uniquePath = self::where('path', $reData['path'])->where('mid', '!=', $info['mid'] ?? 0)->first();
            if ($uniquePath)
                return error_build(DEMON_CODE_PARAM, app('admin')->access->getLang('error_path_unique'));
        }
        $field = self::fieldList();
        foreach ($reData as $key => $val) {
            if (!in_array($key, $field))
                unset($reData[$key]);
        }

        return $reData;
    }

    public static function add($data = [])
    {
        $reData = self::checkData($data);
        if (!error_check($reData))
            return $reData;
        $reData += ['createTime' => mstime()];

        $reData['mid'] = self::insertGetId($reData);

        return $reData['mid'] ? $reData : DEMON_CODE_DATA;
    }

    public static function edit($mid, $data = [])
    {
        $info = self::find($mid);
        if (!$info)
            return DEMON_CODE_PARAM;
        $reData = self::checkData($data, $info);
        if (!error_check($reData))
            return $reData;
        $reData += ['updateTime' => mstime()];
        foreach ($reData as $key => $val) {
            if ($val == $info->{$key})
                unset($reData[$key]);
        }

        return self::where('mid', $mid)->update($reData) ? true : DEMON_CODE_DATA;
    }

    public static function del($mid)
    {
        $store = self::fieldStore();
        if (is_array($mid)) {
            $list = self::whereIn('mid', $mid)->get();
            if (!$list)
                return DEMON_CODE_DATA;
        }
        else {
            $info = self::find($mid);
            if (!$info)
                return DEMON_CODE_DATA;
            $list = [$info];
        }
        $mids = [];
        foreach ($list as $item)
            $mids = array_merge($mids, array_merge([$item['mid']], collect(app('admin')->access->getAccessChilds($store['parent'], $mid))->pluck('mid')->toArray()));

        return self::whereIn('mid', $mids)->where('system', 0)->delete() ? true : DEMON_CODE_DATA;
    }
}
