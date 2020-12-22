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


class Html
{
    /**
     * @var object 对象实例
     */
    protected static $instance;

    /**
     * 初始化
     * @return object|static
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    static function instance()
    {
        if (is_null(self::$instance))
            self::$instance = new static();

        return self::$instance;
    }

    /**
     * 获取HTML实体对象
     *
     * @param $html
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function entity($html)
    {
        return htmlspecialchars(str_replace(["\r", "\n", "\r\n", PHP_EOL, "&nbsp;"], "", (string)nl2br($html)));
    }

    /**
     * 自动生成卡片头部内容
     *
     * @param string $title
     * @param string $content
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function card($title = '', $content = '')
    {
        return "<h5>{$title}<small>{$content}</small></h5><div class='ibox-tools'><a class='collapse-link'><i class='fa fa-chevron-up'></i></a></div>";
    }

    /**
     * 自动生成快速标签
     *
     * @param string $title
     * @param array  $attribute
     * @param string $type
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function fast($title = '', $attribute = [], $type = 'a', $class = '')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val) {
            $append .= "{$key}='{$val}' ";
        }
        //  相同内容
        $common = "class='{$class}' title='{$title}' {$append}";

        //  生成内容
        return "<{$type} {$common}>{$title}</{$type}>" . PHP_EOL;
    }

    /**
     * 生成图片元素
     *
     * @param     $url
     * @param int $width
     * @param int $height
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function image($url, $width = 50, $height = 50, $class = '')
    {
        return "<img class='{$class}' src='{$url}' style='width:{$width}px;height:{$height}px;' data-type='image'/>" . PHP_EOL;
    }

    /**
     * 自动生成按钮内容
     *
     * @param string $title
     * @param array  $attribute
     * @param string $type
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function button($title = '', $attribute = [], $type = 'a', $class = 'btn-xs btn-primary')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val) {
            $append .= "{$key}='{$val}' ";
        }
        //  相同内容
        $common = "class='btn {$class}' title='{$title}' {$append}";

        //  生成内容
        return "<{$type} {$common}>{$title}</{$type}>" . PHP_EOL;
    }

    /**
     * 自动生成复选标签
     *
     * @param array  $attribute
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function batch($attribute = [], $class = 'i-checks')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val) {
            $append .= "{$key}='{$val}' ";
        }

        //  生成内容
        return "<input type='checkbox' class='{$class}' data-bind='batch' {$append}>" . PHP_EOL;
    }

    /**
     * 自动生成Input标签
     *
     * @param string $title
     * @param array  $attribute
     * @param string $type
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function input($title = '', $attribute = [], $type = 'text', $class = '')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val) {
            $append .= "{$key}='{$val}' ";
        }

        //  生成内容
        if ($title)
            return "<label class='{$class}';><input type='{$type}' {$append}>{$title}</label>" . PHP_EOL;
        else
            return "<input type='{$type}' class='{$class}' {$append}>" . PHP_EOL;
    }

    /**
     * 创建搜索控件
     *
     * @param        $data
     * @param string $type
     * @param array  $parm
     *
     * @return array
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function search($data, $type = 'select', $parm = [])
    {
        //  初始化变量
        $newData = [];
        //  类型判断
        switch ($type) {
            // 选择控件
            case 'select':
                // 如果目标是对象，并且有子值
                if (is_object($data) && isset($data->data)) {
                    $parm = array_merge(object_to_array($data), $parm);
                    $data = bomber()->objectClone($data->data);
                }
                $keys = $parm['keys'] ?? '';
                foreach ($data as $key => $val) {
                    // 如果是对象处理
                    if (is_object($data)) {
                        // 如果是一维对象的话，则直接转换为数组
                        if (!is_object($val))
                            $newData[$keys ? $val : $key] = $val;
                        // 如果是二维对象的话，转成数组，值由指定的成员名来获取
                        else if ($keys && is_object($val))
                            $newData[$key] = $val->{$keys};
                    }
                    // 如果是数组处理
                    else if (is_array($data)) {
                        // 如果是一维数组的话，则直接保留结构
                        if (bomber()->arrayLevel($data) == 1)
                            $newData[$keys ? $val : $key] = $val;
                        // 如果是二维数组的话，和对象相同的处理
                        else if ($keys && bomber()->arrayLevel($data) == 2)
                            $newData[$key] = $val[$keys];
                    }
                }
                break;
        }

        return $newData;
    }
}
