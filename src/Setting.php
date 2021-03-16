<?php

namespace Demon\AdminLaravel;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Setting
 * @package Demon\AdminLaravel
 */
class Setting
{
    /**
     * @var Collection
     */
    public $list;

    /**
     * @var array
     */
    public $module = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * Setting constructor.
     */
    public function __construct($list)
    {
        $this->setList($list);
    }

    /**
     * 设置列表
     *
     * @param $list
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setList($list)
    {
        $this->list = !is_array($list) ? object_to_array($list) : $list;
        foreach ($this->list as $key => $val)
            $this->list[$key] = collect($val);
        $this->list = collect($this->list);
        foreach ($this->list as $key => $val)
            $this->list[$key]['value'] = self::format($val['value'], $val['filter']);
        foreach (array_keys($this->list->groupBy('module')->toArray()) as $module)
            $this->module[$module] = Str::title($module);
        $this->setData();

        return $this;
    }

    /**
     * 设置数据
     *
     * @param null $list
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setData($list = null)
    {
        $this->data = $list === null ? [] : array_merge($this->data, $list);
        foreach ($this->list as $key => $val) {
            $this->list[$key]['data'] = (is_string($val['data']) ? json_decode($val['data']) : $val['data']) ? : [];
            $init = is_string($val['validate']);
            $this->list[$key]['validate'] = ($init ? json_decode($val['validate'], true) : $val['validate']) ? : [];
            $this->list[$key]['validateAttr'] = $val['validateAttr'] ?? [];
            if ($init) {
                $validate = [];
                foreach ($this->list[$key]['validate'] as $rule => $content)
                    $validate[] = $rule . '="' . (is_bool($content) ? ($content ? 'true' : null) : $content) . '"';
                $this->list[$key]['validateAttr'] = $validate;
            }
            foreach ($this->data as $tag => $data) {
                if ($val['tag'] == $tag)
                    $this->list[$key]['data'] = (is_string($data) ? json_decode($data) : $data) ? : [];
            }
            if (in_array($val['type'], ['range', 'between'])) {
                $data = object_to_array($val['data']);
                $data = ['min' => $data['min'] ?? 0, 'max' => $data['max'] ?? 100, 'step' => $data['step'] ?? 1, 'grid' => $data['grid'] ?? false];
                $attrData = [];
                foreach ($data as $k => $v)
                    $attrData[] = 'data-' . $k . '="' . (is_bool($v) ? ($v ? 'true' : null) : $v) . '"';
                $this->list[$key]['attr'] = $attrData;
            }
        }

        return $this;
    }

    /**
     * 变量类型转换
     *
     * @param        $value
     * @param string $filter
     *
     * @return mixed
     * @copyright 魔网天创信息科技
     *
     * @author    ComingDemon
     */
    public function format($value, $filter = 'string')
    {
        //  字段类型
        switch (strtolower($filter)) {
            //  转换为数组
            case 'array':
                if (is_object($value))
                    $value = collect($value)->toArray();
                else if (!is_array($value)) {
                    if (!bomber()->regexp($value, 'json'))
                        $value = explode(',', $value);
                    else $value = bomber()->regexp($value, 'json') ? json_decode($value, true) : [];
                }
                break;
            //  转换为对象
            case 'object':
                if (is_array($value))
                    $value = array_to_object($value);
                else if (!is_object($value)) {
                    if (!bomber()->regexp($value, 'json'))
                        $value = explode(',', $value);
                    else $value = bomber()->regexp($value, 'json') ? json_decode($value) : new \stdClass();
                }
                break;
            default:
                $value = bomber()->typeCast($value, $filter);
                break;
        }

        //  返回结果
        return $value;
    }

    /**
     * 获取全部内容或者根据模块获取内容
     *
     * @param string $module
     *
     * @return Collection
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getList(string $module = '')
    {
        //  申明内容
        $data = [];
        //  如果查询具体模块
        if ($module) {
            $list = $this->list->where('module', $module);
            if ($list) {
                foreach ($list as $val)
                    $data[$val['name']] = self::format($val['value'], $val['filter']);
            }
        }
        //  否则查询全部
        else {
            $list = $this->list;
            if ($list) {
                foreach ($list as $val) {
                    //  根据模块分组
                    if (!isset($data[$val['module']]))
                        $data[$val['module']] = [];
                    $data[$val['module']][$val['name']] = self::format($val['value'], $val['filter']);
                }
            }
        }

        //  返回结果
        return collect($data);
    }

    /**
     * 设置模块标题
     *
     * @param array $list
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setModule($list = [])
    {
        foreach ($list as $module => $name) {
            if (isset($this->module[$module]))
                $this->module[$module] = $name;
        }

        return $this;
    }

    /**
     * 获取全部模块
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * 获取指定模块中的变量
     *
     * @param string $module
     * @param string $name
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getValue(string $module, string $name)
    {
        //  获取数据
        if (!($data = $this->list->where('module', $module)->where('name', $name)->first()))
            return null;

        //  返回转换后内容
        return self::format($data['value'], $data['filter']);
    }

    /**
     * 更新指定模块中的变量
     *
     * @param       $module
     * @param       $name
     * @param       $value
     * @param array $where
     *
     * @return mixed
     * @copyright 魔网天创信息科技
     *
     * @author    ComingDemon
     */
    public function setValue(string $module, string $name, $value, $where = [])
    {
        //  更新类型
        $type = '=';
        if (is_array($value)) {
            $type = $value[1];
            $value = $value[0];
        }

        //  对象或者数组转换为JSON串
        if (is_array($value) || is_object($value))
            $value = json_encode(JSON_UNESCAPED_UNICODE) ? : '[]';

        //  获取内容
        $data = $this->list->where('module', $module)->where('name', $name)->first();
        if (!$data || (in_array($data['filter'], ['array', 'object']) && $type != '='))
            return DEMON_CODE_DATA;

        //  最新数据
        $after = $data['value'];
        switch ($type) {
            case '+':
                $after = $before + $value;
                break;
            case '-':
                $after = $before - $value;
                break;
        }

        //  都转换为字符串
        $before = (string)$before;
        $after = (string)$after;

        //  如果变更前后相同
        if ($before != $after) {
            $data['value'] = $after;
            $data['before'] = $before;
        }

        //  返回对比结果
        return ['before' => $before, 'after' => $after];
    }

    /**
     * 获取指定模块的内容
     *
     * @return Collection
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function get($module)
    {
        return $this->list->whereIn('module', is_array($module) ? $module : [$module])->whereIn('hidden', [-1, 0])->sortBy('id')->sortBy('reorder');
    }

    /**
     * 批量更新
     *
     * @param        $module
     * @param array  $data
     *
     * @return mixed|null
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     *
     */
    public function save($module = null, array $data = [])
    {
        //  保存配置
        $change = [];
        if ($list = $this->list->where('module', $module)->whereIn('hidden', [-1, 0])->sortBy('id')->sortBy('reorder')) {
            foreach ($list as $item) {
                //  其他类型如果没值或者设置了隐藏状态则直接跳过，如果没传也跳过
                if ($item['hidden'] != 0 || !isset($data[$item['name']]))
                    continue;
                //  特殊字段特殊处理
                switch ($item['filter']) {
                    case 'array':
                    case 'object':
                        $change[$item['name']] = $data[$item['name']];
                        //  如果是字符串则转换为数组
                        if (is_string($change[$item['name']])) {
                            if (!bomber()->regexp($change[$item['name']], 'json'))
                                $change[$item['name']] = explode(',', $change[$item['name']]);
                            else $change[$item['name']] = json_decode($change[$item['name']], true);
                        }
                        //  循环处理一下浮点型和整数型去掉字符串的引号
                        foreach ($change[$item['name']] as $k => $v) {
                            if (mb_strlen((int)$v) == mb_strlen($v))
                                $change[$item['name']][$k] = (int)$v;
                            else if (mb_strlen((float)$v) == mb_strlen($v))
                                $change[$item['name']][$k] = (float)$v;
                            else
                                $change[$item['name']][$k] = $v;
                        }
                        break;
                    default:
                        $change[$item['name']] = bomber()->typeCast($data[$item['name']], $item['filter']);
                        break;
                }
                //  如果是一样的数据就销毁掉
                if ($change[$item['name']] == $item['value'])
                    unset($change[$item['name']]);
            }
        }
        $differ = [];
        if ($change) {
            //  逐条更新
            $this->list->where('module', $module)->whereIn('hidden', [-1, 0])->map(function($item) use (&$change, &$differ) {
                foreach ($change as $name => $value) {
                    if ($item['name'] == $name) {
                        $differ[$name] = ['before' => $item['value'], 'after' => $value];
                        $item['value'] = $value;
                        $change[$name] = is_object($value) || is_array($value) ? (json_encode($value, JSON_UNESCAPED_UNICODE) ? : '[]') : $value;
                        break;
                    }
                }

                return $item;
            });
        }

        //  返回差异化和变更内容
        return ['differ' => $differ, 'change' => $change];
    }

    /**
     * 渲染页面数据
     *
     * @param       $view
     * @param       $module
     * @param array $data
     * @param array $mergeData
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function render($view, $module, $data = [], $mergeData = [])
    {
        return admin_view($view, $data, $mergeData)->with('list', self::get($module));
    }
}
