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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use stdClass;

class DBTable
{
    /**
     * @var string[] 模板内容
     */
    public $template = ['script' => '', 'search' => '', 'button' => ''];

    /**
     * @var array 自定义储存
     */
    public $store = [];

    /**
     * @var object 完整配置项
     */
    public $config = null;

    /**
     * @var array 预制配置信息
     */
    public $preset = [];

    /**
     * @var array 字段配置
     */
    public $column = [];

    /**
     * @var array 字段映射
     */
    public $field = [];

    /**
     * @var array 排序设置
     */
    public $order = [];

    /**
     * @var object 搜索设置
     */
    public $search = null;

    /**
     * @var array 模糊搜索
     */
    public $fuzzy = [];

    /**
     * @var DB 查询设置
     */
    public $query = null;

    /**
     * @var array 按钮定义
     */
    public $button = [];

    /**
     * DataTable constructor.
     */
    public function __construct()
    {
        $this->getConfig();
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
     * 表格配置定义
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getConfig()
    {
        //  读取总配置
        $this->config = $this->config ? : array_to_object(array_merge(config('admin.dbtable'), $this->setConfig(), $this->getPreset()));
        //  读取字段
        $this->config->columns = array_to_object($this->getColumn());
        //  JS作用域
        $this->config->namespace = $this->config->namespace ?? '$.admin.table';
        //  设置URL
        $this->config->url = ($this->config->url ?? null) ? : url()->current();
        //  开启批量选择
        if (($this->config->batch ?? false) && !($this->config->columns[0]->checkbox ?? false))
            array_unshift($this->config->columns, ['checkbox' => true]);

        //  返回配置
        return $this->config;
    }

    /**
     * 设置前置配置
     *
     * @param      $key
     * @param null $val
     *
     * @return $this
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setPreset($key, $val = null)
    {
        if (is_string($key))
            $this->preset[$key] = $val;
        else
            $this->preset = array_merge($this->preset, $key);
        //  合并到配置项
        $this->config = bomber()->objectMerge($this->config, $this->preset);

        return $this;
    }

    /**
     * 获取前置配置
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * 设置额外字段
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setField()
    {
        return [];
    }

    /**
     * 设置字段
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getField()
    {
        return $this->field = $this->setField() + $this->field;
    }

    /**
     * 表格按钮定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setButton()
    {
        return [];
    }

    /**
     * 表格按钮定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getButton()
    {
        //  循环处理
        $list = $this->setButton();
        foreach ($list as $key => &$val) {
            $val['text'] = $val['text'] ?? '';
            $val['class'] = ($val['class'] ?? '') ? : $this->config->toolbarButton ?? 'btn btn-secondary';
            $val['title'] = $val['title'] ?? '';
            $val['attr'] = $val['attr'] ?? [];
            $val['icon'] = $val['icon'] ?? '';
            $val['text'] = ($val['icon'] ? adminHtml()->fast('', [], 'i', $val['icon']) : '') . $val['text'];
            if ($val['title']) {
                $val['attr']['title'] = $val['title'];
                $val['attr']['data-toggle'] = 'tooltip';
                $val['attr']['data-trigger'] = 'hover';
            }
            $attribute = '';
            foreach ($val['attr'] as $k => $v)
                $attribute .= " {$k}='$v'";
            $val['html'] = "<button class='{$val['class']}' {$attribute} data-button-key='{$key}'>{$val['text']}</button>";
        }

        return $this->button = array_to_object($list);
    }

    /**
     * 表格标题定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setColumn()
    {
        return [];
    }

    /**
     * 获取字段构造
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getColumn()
    {
        //  循环处理
        $array = $this->setColumn();
        foreach ($array as $key => &$val) {
            //  操作列强制设定
            if ($val['action'] ?? false)
                $val['clickToSelect'] = $val['reorder'] = $val['export'] = $val['search'] = $val['print'] = false;
            //  处理宽度
            if ($val['width'] ?? null) {
                if (stripos($val['width'], 'rem') !== false)
                    $val['widthUnit'] = 'rem';
                if (stripos($val['width'], '%') !== false)
                    $val['widthUnit'] = '%';
            }
            //  默认排序关闭
            $val['sortable'] = $val['sortable'] ?? ($val['reorder'] ?? false);
            //  默认关联排序
            if ($val['sortable'] && !is_bool($val['sortable']))
                $val['orderData'] = $val['orderData'] ?? ($val['sortable'] ?? []);
            //  默认搜索关闭
            $val['searchable'] = $val['searchable'] ?? ($val['search'] ?? false);
            //  默认导出开启
            $val['exporIgnore'] = $val['exporIgnore'] ?? !($val['export'] ?? true);
            if ($val['exporIgnore'])
                $this->config->ignoreColumn[] = $key;
            //  默认打印开启
            $val['printIgnore'] = $val['printIgnore'] ?? !($val['print'] ?? true);
            //  如果data是带.的表示关联查询，将其填补name，并且data去掉关联
            if (!isset($val['name']) && strpos($val['data'], '.') !== false) {
                $val['name'] = $val['data'];
                $val['data'] = preg_replace('/.*\./', '', $val['data']);
                $this->field[$val['data']] = $val['name'];
            }
            else if (!($val['custom'] ?? false))
                $this->field[$val['data']] = $val['data'];
            //  对应字段
            $val['field'] = $val['field'] ?? $val['data'];
            unset($val['data']);
            //  是否有事件
            if ($val['action'] ?? false && !(($val['events'] ?? null)))
                $val['events'] = $this->config->namespace . '.' . ($this->config->actionEvent ?? 'action');
        }

        //  返回字段
        return $this->column = $array;
    }

    /**
     * 设置格式化
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setFormat()
    {
        return [];
    }

    /**
     * 格式化内容
     *
     * @param       $array
     * @param array $array
     *
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getFormat($list)
    {
        //  获取表字段
        $rawColumns = [];
        //  循环处理字段内容
        foreach ($this->setFormat() as $key => $val) {
            //  处理类型
            $type = is_array($val) ? ($val['type'] ?? '') : '';
            //  处理方法
            $callback = is_array($val) ? ($val['callback'] ?? null) : $val;
            $isHtml = is_array($val) ? ($val['html'] ?? true) : true;
            //  处理类型
            switch ($type) {
                //  添加字段
                case 'add':
                    foreach ($list as $k => $v)
                        $list[$k][$key] = $callback ? $callback($v) : null;
                    break;
                //  移除字段
                case 'remove':
                    foreach ($list as $k => $v)
                        unset($list[$k][$key]);
                    break;
                //  编辑字段
                case 'edit':
                default:
                    foreach ($list as $k => $v)
                        $list[$k][$key] = $callback ? $callback($v) : $list[$k][$key];
            }
        }

        //  返回表
        return $list;
    }

    /**
     * 表格排序定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setOrder()
    {
        return [];
    }

    /**
     * 表格排序定义
     * @return array
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function getOrder()
    {
        return $this->order ? : $this->order = $this->setOrder();
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
     * 表格搜索定义
     *
     * @param array $data
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getSearch()
    {
        //  如果已经处理过则直接返回
        if ($this->search)
            return $this->search;
        //  遍历预设内容给JS使用
        $data = $this->setSearch();
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

        //  返回配置
        return $this->search = array_to_object(['list' => $data, 'value' => $value]);
    }

    /**
     * 设置模糊搜索
     *
     * @param null $text
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setFuzzy($text = null)
    {
        $list = [];
        if (trim($text) && ($this->config->search ?? false)) {
            $this->query->where(function($query) use ($text) {
                foreach ($this->config->columns as $val) {
                    if (!($val->searchable ?? false) || !($val->field ?? null))
                        continue;
                    $field = $this->field[$val->field] ?? $val->field;
                    $query->orWhere($field, 'like', "%{$text}%");
                    $list[$field] = $text;
                }
            });
        }

        return $list;
    }

    /**
     * 设置模糊搜索
     *
     * @param null $text
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getFuzzy($text = null)
    {
        return $this->setFuzzy($text);
    }

    /**
     * 设置表单搜索
     *
     * @param array $array
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setSearchs($array = [])
    {
        if ($this->config->searchPanel ?? false) {
            //  获取配置规则
            $list = array_to_object($this->setSearch());
            $searchs = $this->config->searchList ?? 'searchs';
            //  初始化where条件
            $where = [];
            //  规则内容转义
            $format = function($data, $config) {
                //  初始化规则
                $config->rule = $config->rule ?? null;
                //  按照类型格式化
                switch ($config->type ?? '') {
                    //  通用的时间转换
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
                    //  其他自定义
                    default:
                        //  自定义回调
                        if (gettype($config->callback ?? null) == 'function')
                            return $config->callback($data, $config->rule, $config->type);
                        break;
                }

                //  返回转换结果
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
                $val->value = $val->where == 'range' ? [$format(arguer("{$searchs}.{$val->name}__start", ''), $val), $format(arguer("{$searchs}.{$val->name}__end", ''), $val)] : $format(arguer("{$searchs}.{$val->name}", ''), $val);
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
        }

        //  加入条件
        foreach ($where as $val)
            $this->query->where(...$val);

        return $where;
    }

    /**
     * 设置表单搜索
     *
     * @param array $array
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getSearchs($array = [])
    {
        return $this->setSearchs($array);
    }

    /**
     * 设置统计信息
     *
     * @param $query
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setStatis($query)
    {
        return [];
    }

    /**
     * 设置统计信息
     *
     * @param $query
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getStatis($query)
    {
        return $this->setStatis(clone $query);
    }

    /**
     * 查询构造器
     * @return Model|DB
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function setQuery()
    {
        return null;
    }

    /**
     * 查询构造器
     *
     * @param bool $structure
     *
     * @return Model|DB|null
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getQuery($structure = false)
    {
        //  初始化
        $this->query = $this->setQuery();
        //  是否为传统设置
        if ($this->config->queryParamsType == 'limit') {
            $limit = arguer('limit', $this->config->pageSize ?? 15, 'abs');
            $offset = arguer('offset', 0, 'abs');
            $sortName = arguer('sort', $this->config->sortName ?? '', 'xss');
            $sortOrder = arguer('order', $this->config->sortOrder ?? 'asc', 'xss');
            $searchText = arguer('search', $this->config->searchText ?? '', 'xss');
        }
        else {
            $limit = arguer('pageSize', $this->config->pageSize ?? 15, 'abs');
            $offset = (arguer('pageNumber', $this->config->pageNumber ?? 1, 'abs') - 1) * $limit;
            $sortName = arguer('sortName', $this->config->sortName ?? '', 'xss');
            $sortOrder = arguer('sortOrder', $this->config->sortOrder ?? 'asc', 'xss');
            $searchText = arguer('searchText', $this->config->searchText ?? '', 'xss');
        }
        //  模糊搜索
        $this->getFuzzy($searchText);
        //  搜索表单
        $this->getSearchs(arguer($this->config->searchList ?? 'searchs', [], 'array'));

        //  阻断
        if (!$structure)
            return $this->query;
        //  分页限制
        $limit = min($limit, max($this->config->pageList));
        $this->query->offset($offset)->limit($limit);
        //  排序设定
        if ($sortName)
            $this->query->orderBy($this->field[$sortName] ?? $sortName, $sortOrder ? : 'asc');
        //  强制排序
        foreach ($this->getOrder() as $key => $val)
            $this->query->orderBy($this->field[$key] ?? $key, $val);

        //  返回构造
        return $this->query;
    }

    /**
     * 定义或输出脚本模板
     *
     * @param       $type
     * @param array $data
     * @param null  $content
     *
     * @return $this|string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function template($type, $data = [], $content = null)
    {
        //  定义内容
        if ($content !== null) {
            $this->template[$type] = $content;

            return $this;
        }

        //  输出模板
        return view()->make($this->template[$type] ? : config("admin.dbtable.template.{$type}", ''))->with($data)->render();
    }

    /**
     * 输出脚本内容
     *
     * @param null   $content
     * @param string $attributes
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function script($content = null, $attributes = 'type="text/javascript"')
    {
        //  获取事件
        $events = [];
        foreach ($this->column as $key => $val) {
            if ($val['events'] ?? null)
                $events[] = "{$val['events']}={$val['events']}||{}";
        }
        //  生成脚本
        $content = $content ? : new HtmlString(sprintf($this->template('script'), $this->config->namespace, $this->config->id ?? 'dbTable', json_encode($this->config), implode(';', $events)));
        //  脚本执行后是否删除
        if (!config('app.debug'))
            $content .= '$(function(){$("#dbTableScript").remove()})';

        //  返回视图文本
        return (new HtmlString("<script id='dbTableScript' {$attributes}>{$content}</script>\n"))->toHtml();
    }

    /**
     * 输出搜索面板
     *
     * @param null $content
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function search($content = null)
    {
        //  生成脚本
        $content = $content ? : new HtmlString($this->template('search', ['dbTableConfig' => $this->config, 'dbTableSearch' => $this->search]));

        //  返回视图文本
        return (new HtmlString("{$content}\n"))->toHtml();
    }

    /**
     * 输出按钮区域
     *
     * @param null $content
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function button($content = null)
    {
        //  生成脚本
        $content = $content ? : new HtmlString($this->template('button', ['dbTableConfig' => $this->config, 'dbTableButton' => $this->button]));

        //  返回视图文本
        return (new HtmlString("{$content}\n"))->toHtml();
    }

    /**
     * 输出表格内容
     *
     * @param null $content
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function table($content = null)
    {
        //  返回视图文本
        return (new HtmlString($content ? : "<table id='" . ($this->config->id ?? 'dbTable') . "'></table>\n"))->toHtml();
    }

    /**
     * 快捷输出表格
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function html()
    {
        //  返回视图文本
        return $this->search() . $this->button() . (new HtmlString("<table id='" . ($this->config->id ?? 'dbTable') . "'>" . $this->script() . "</table>\n"))->toHtml();
    }

    /**
     * 查询数据集并打包格式
     * @return JsonResponse
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function ajax()
    {
        $data = [$this->config->totalField => $this->getQuery(false)->count()];
        if (config('app.debug'))
            $data['_debug'] = [$this->config->totalField => sprintf(str_replace('?', '%s', $this->query->toSql()), ...$this->query->getBindings())];
        if ($statis = $this->getStatis($this->query))
            $data['statis'] = $statis;
        $data[$this->config->dataField] = $this->getFormat($this->getQuery(true)->select(array_values($this->field))->get());
        if (config('app.debug'))
            $data['_debug'][$this->config->dataField] = sprintf(str_replace('?', '%s', $this->query->toSql()), ...$this->query->getBindings());

        //  输出JSON
        return new JsonResponse($data);
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
        //  指定字段
        $this->getField();
        //  指定搜索
        $this->getSearch();
        //  如果是AJAX则表示查询数据
        if (DEMON_INAJAX)
            return $this->ajax();
        //  渲染按钮
        $this->getButton();

        //  渲染视图
        return view($view, $data, $mergeData)->with('dbTable', $this);
    }
}
