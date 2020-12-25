<?php
/**
 * 本文件用于定义一些运维后台相关的内容
 * Created by M-Create.Team
 * Copyright 魔网天创信息科技
 * User: ComingDemon
 * Date: 2020/12/22
 * Time: 15:44
 */

namespace Demon\AdminLaravel;

use stdClass;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class DataTable extends \Yajra\DataTables\Services\DataTable
{
    /**
     * @var array 自定义储存
     */
    public $store = [];
    /**
     * @var array 自定义配置
     */
    protected $custom = [];
    /**
     * @var array 完整配置项
     */
    protected $config = [];

    /**
     * @var array 预制配置信息
     */
    protected $preset = [];

    /**
     * DataTable constructor.
     */
    public function __construct()
    {
    }

    /**
     * 渲染页面数据
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return mixed
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function render($view, $data = [], $mergeData = [])
    {
        $this->getSearch($this->setSearch());

        return parent::render($view, $data, $mergeData);
    }

    /**
     * 字段构造方法
     *
     * @param       $query
     * @param array $array
     *
     * @return DataTableAbstract|DataTables
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function makeColumn($query, $array = [])
    {
        //  定义表信息
        $table = datatables($query);
        //  获取表字段
        $rawColumns = [];
        //  循环处理字段内容
        foreach ($array as $key => $val) {
            //  处理类型
            $type = is_array($val) ? ($val['type'] ?? '') : '';
            //  处理方法
            $callback = is_array($val) ? ($val['callback'] ?? null) : $val;
            $isHtml = is_array($val) ? ($val['html'] ?? true) : true;
            if ($isHtml)
                $rawColumns[] = $key;
            //  处理类型
            switch ($type) {
                //  添加字段
                case 'add':
                    $table->addColumn($key, $callback);
                    break;
                //  移除字段
                case 'remove':
                    $table->removeColumn($key);
                    break;
                //  编辑字段
                case 'edit':
                default:
                    $table->editColumn($key, $callback);
            }
        }
        $table->rawColumns($rawColumns);

        //  返回表
        return $table;
    }

    /**
     * 默认生成字段表格
     *
     * @param $query
     *
     * @return DataTableAbstract|DataTables
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function dataTable($query)
    {
        return $this->makeColumn(datatables($query));
    }

    /**
     * 获取字段构造
     *
     * @param array $array
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getColumn($array = [])
    {
        foreach ($array as $key => &$val) {
            //  批量选择器强制设定
            if ($val['data'] == '_batch') {
                $val['width'] = '22px';
                $val['title'] = '';
                $val['className'] = $val['data'];
                $val['shrink'] = 1;
                $val['reorder'] = $val['export'] = $val['search'] = $val['print'] = false;
            }
            //  操作列强制设定
            if ($val['data'] == '_action') {
                $val['className'] = $val['data'];
                $val['shrink'] = 1;
                $val['reorder'] = $val['export'] = $val['search'] = $val['print'] = false;
            }
            //  默认排序关闭
            $val['orderable'] = $val['orderable'] ?? ($val['reorder'] ?? false);
            //  默认关联排序
            if ($val['orderable'] && !is_bool($val['orderable']))
                $val['orderData'] = $val['orderData'] ?? ($val['orderable'] ?? []);
            //  默认搜索关闭
            $val['searchable'] = $val['searchable'] ?? ($val['search'] ?? false);
            //  默认导出开启
            $val['exportable'] = $val['exportable'] ?? ($val['export'] ?? true);
            //  默认打印开启
            $val['printable'] = $val['printable'] ?? ($val['print'] ?? true);
            //  默认搜索关闭
            $val['searchable'] = $val['searchable'] ?? ($val['search'] ?? false);
            //  默认收缩权重（越大越被收缩）
            $val['responsivePriority'] = $val['responsivePriority'] ?? ($val['shrink'] ?? 10000);
            //  如果data是带.的表示关联查询，将其填补name，并且data去掉关联
            if (!isset($val['name']) && strpos($val['data'], '.') !== false) {
                $val['name'] = $val['data'];
                $val['data'] = preg_replace('/.*\./', '', $val['data']);
            }
        }

        return $array;
    }

    /**
     * 表格标题定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getThead()
    {
        return [];
    }

    /**
     * 表格排序定义
     * @return array[]
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getOrder()
    {
        return [
            'norm' => [],
            'before' => [],
            'after' => [],
        ];
    }

    /**
     * 表格搜索定义
     *
     * @param array $data
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getSearch($data = [])
    {
        //  遍历预设内容给JS使用
        $value = [];
        foreach ($data as $key => $val) {
            // 将name的表去掉
            $name = $val['name'] ?? $val['data'];
            if ($name == $val['data'] && $strrpos = strrpos($name, '.') !== false)
                $data[$key]['name'] = $val['name'] = substr($name, $strrpos + 1);
            // 如果有预设默认值
            if (isset($val['value'])) {
                if (($val['where'] ?? '') == 'range') {
                    $value[$val['name'] . '__start'] = $val['value'][0] ?? '';
                    $value[$val['name'] . '__end'] = $val['value'][1] ?? '';
                }
                else
                    $value[$val['name']] = $val['value'];
            }
            // 如果有参数的控件
            if (isset($val['attr'])) {
                $data[$key]['attrHtml'] = '';
                foreach ($val['attr'] as $k => $a)
                    $data[$key]['attrHtml'] .= ($k . '="' . $a . '"');
            }
        }

        //  读取配置
        $config = $this->getConfig($this->setConfig());
        //  拼接参数
        $config['serverParams'] = $config['serverParams'] ? : "function(data){data.search.list=$.dataTable.searchParams();}";
        //  传递到视图
        view()->share('dataTableSearch', array_to_object([
            'status' => $config['searching'],
            'template' => $config['searchTemplate'],
            'form' => $config['searchForm'],
            'list' => $data,
            'value' => $value
        ]));

        //  返回配置
        return $config;
    }

    /**
     * 表格搜索获取
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setSearch()
    {
        return [];
    }

    /**
     * 创建查询语句
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function makeSearch()
    {
        //  获取配置规则
        $list = array_to_object($this->setSearch());
        //  初始化where条件
        $where = [];
        //  规则内容转义
        $format = function($data, $config) {
            //  初始化规则
            $config->rule = $config->rule ?? new stdClass();
            //  按照类型格式化
            switch ($config->type ?? '') {
                case 'time':
                    switch ($config->rule->format ?? 'string') {
                        case 's':
                            $data = strtotime($data, DEMON_TIME);
                            break;
                        case 'ms':
                            $data = strtotime($data, DEMON_TIME) * 1000;
                            break;
                    }
                    break;
            }

            return $data;
        };
        //  遍历规则
        foreach ($list as $val) {
            //  将name的表去掉
            $val->name = $val->name ?? $val->data;
            if ($val->name == $val->data && $strrpos = strrpos($val->name, '.') !== false)
                $val->name = substr($val->name, $strrpos + 1);
            //  初始化where
            $val->where = $val->where ?? '=';
            //  如果是范围搜索，则拼接start和end
            $val->value = $val->where == 'range' ? [$format(arguer("search.list.{$val->name}__start", ''), $val), $format(arguer("search.list.{$val->name}__end", ''), $val)] : $format(arguer("search.list.{$val->name}", ''), $val);
            //  初始化连接符
            $val->joint = $val->joint ?? 'and';
            // 如果有内容的话开始拼接where条件
            if ($val->value !== '') {
                switch ($val->where) {
                    //  自定义
                    case 'custom':
                        break;
                    //  范围区间
                    case 'range':
                        if ($val->value[0])
                            $where[] = [$val->data, '>=', $val->value[0], $val->joint];
                        if ($val->value[1])
                            $where[] = [$val->data, '<=', $val->value[1], $val->joint];
                        break;
                    //  全部模糊匹配
                    case 'like':
                        $where[] = [$val->data, $val->where, '%' . $val->value . '%', $val->joint];
                        break;
                    //  后缀模糊匹配
                    case 'like%':
                        $where[] = [$val->data, $val->where, $val->value . '%', $val->joint];
                        break;
                    //  前缀模糊匹配
                    case '%like':
                        $where[] = [$val->data, $val->where, '%' . $val->value, $val->joint];
                        break;
                    //  IN或者NOTIN
                    case 'in':
                    case 'notin':
                        if (!is_array($val->value))
                            $val->value = explode(',', $val->value);
                        $where[] = [$val->data, $val->where, $val->value, $val->joint];
                        break;
                    //  比较符
                    case '=':
                    case '>':
                    case '>=':
                    case '<':
                    case '<=':
                    default:
                        $where[] = [$val->data, $val->where, $val->value, $val->joint];
                        break;
                }
            }
        }

        return $where;
    }

    /**
     * 表格按钮定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getButton()
    {
        return [];
    }

    /**
     * 表格配置定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setConfig()
    {
        return [];
    }


    /**
     * 设置私有信息
     *
     * @param      $key
     * @param null $val
     *
     * @return $this
     */
    public function setPreset($key, $val = null)
    {
        if (is_string($key))
            $this->preset[$key] = $val;
        else
            $this->preset = array_merge($this->preset, $key);

        return $this;
    }

    /**
     * 获取私有信息
     * @return array
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * 表格配置定义
     *
     * @param array $custom
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getConfig($custom = [])
    {
        //  读取并移除渲染配置
        $render = config('admin.datatable.render') ? : [];
        config(['admin.datatable.render' => null]);
        //  更新到渲染器
        foreach ($render as $key => $val)
            config(["datatables-html.{$key}" => $val]);
        //  获取排序配置
        $order = $this->getOrder();
        //  默认排序
        $custom['order'] = $custom['order'] ?? ($order['norm'] ?? []);
        //  锁定排序
        $custom['orderFixed'] = $custom['orderFixed'] ?? [];
        //  排序前置
        $custom['orderFixed']['pre'] = $custom['orderFixed']['pre'] ?? ($order['before'] ?? []);
        //  排序后置
        $custom['orderFixed']['post'] = $custom['orderFixed']['post'] ?? ($order['after'] ?? []);
        //  是否允许模糊搜索
        $custom['searchDelay'] = $custom['searchLike'] ?? false;
        //  获取按钮配置
        $custom['buttons'] = $custom['buttons'] ?? ($this->getButton() ?? []);
        //  默认三件套之导出
        if ($custom['buttons']['excel'] ?? false)
            $custom['buttons'][] = ['extend' => 'excel', 'className' => 'btn btn-sm btn-success', 'text' => '导出'];
        unset($custom['buttons']['excel']);
        //  默认三件套之重载
        if ($custom['buttons']['reset'] ?? false)
            $custom['buttons'][] = ['extend' => 'reset', 'className' => 'btn btn-sm btn-success', 'text' => '重载'];
        unset($custom['buttons']['reset']);
        //  默认三件套之刷新
        if ($custom['buttons']['reload'] ?? true)
            $custom['buttons'][] = ['extend' => 'reload', 'className' => 'btn btn-sm btn-success', 'text' => '刷新'];
        unset($custom['buttons']['reload']);
        //  自定义按钮
        foreach ($custom['buttons'] as $key => $val) {
            //  如果是自定义扩展按钮
            if (is_string($key)) {
                $custom['buttons'][] = ['extend' => $key, 'className' => $val['class'] ?? 'btn btn-sm btn-info', 'text' => $val['text'] ?? $key];
                unset($custom['buttons'][$key]);
            }
        }

        //  返回合并后的配置
        $this->custom = array_merge($custom);
        $this->config = array_merge(config('admin.datatable'), $this->preset, $custom);

        return $this->config;
    }

    /**
     * 表格样式定义
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getClass()
    {
        return 'table-bordered table-striped table-hover display';
    }

    /**
     * 输出最终HTML页面
     * @return Builder
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function html()
    {
        return $this->builder()->columns($this->getThead())->addTableClass($this->getClass())->parameters($this->getSearch($this->setSearch()));
    }
}
