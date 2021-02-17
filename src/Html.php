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
     * 自动生成快速标签
     *
     * @param string $title
     * @param array  $attribute
     * @param string $tag
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function fast($title = '', $attribute = [], $tag = 'a', $class = '')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val)
            $append .= "{$key}='{$val}' ";
        //  相同内容
        $common = "class='{$class}' {$append}";

        //  生成内容
        return "<{$tag} {$common}>{$title}</{$tag}>" . PHP_EOL;
    }

    /**
     * 生成图片元素
     *
     * @param        $url
     * @param array  $attribute
     * @param int[]  $size
     * @param string $class
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function image($url, $attribute = [], $size = ['30px', '30px'], $class = '')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val)
            $append .= "{$key}='{$val}' ";
        //  相同内容
        $common = "class='{$class}' {$append}";
        if (is_array($size)) {
            $width = $size[0] ?? '30px';
            $height = $size[1] ?? $width;
        }
        else  $width = $height = $size;

        //  生成内容
        return "<img {$common} src='{$url}' style='width:{$width};height:{$height};' data-bind='image'/>" . PHP_EOL;
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
    public function button($title = '', $attribute = [], $type = 'a', $class = 'btn-sm btn-primary')
    {
        //  属性列表
        $append = '';
        foreach ($attribute as $key => $val)
            $append .= "{$key}='{$val}' ";
        //  相同内容
        $common = "class='btn {$class}' {$append}";

        //  生成内容
        return "<{$type} {$common}>{$title}</{$type}>" . PHP_EOL;
    }

    /**
     * 自动生成开关标签
     *
     * @param array  $attribute
     * @param bool   $status
     * @param string $class
     *
     * @return string
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function switch($attribute = [], $status = false, $class = 'switch hidden')
    {
        //  属性列表
        $append = ($status ? 'checked' : '');
        foreach ($attribute as $key => $val)
            $append .= " {$key}='{$val}'";

        //  生成内容
        return "<input class='{$class}' type='checkbox' data-bind='switch' {$append}>" . PHP_EOL;
    }

    /**
     * 输入框
     *
     * @param array  $attribute
     * @param string $class
     * @param array  $group
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function input($attribute = [], $class = 'form-control', $group = [])
    {
        $append = '';
        foreach ($attribute as $key => $val)
            $append .= " {$key}='{$val}'";
        $input = "<input type='text' class='form-control {$class}' {$append}/>";
        if (!$group)
            return $input;
        $class = $group['class'] ?? 'input-group';
        $attr = '';
        foreach ($group['attr'] ?? [] as $key => $val)
            $attr .= " {$key}='{$val}'";
        $html = "<div class='{$class}' {$attr}>";
        if ($group['prepend'] ?? '') {
            $html .= '<div class="input-group-prepend">';
            $html .= $group['append'];
            $html .= '</div>';
        }
        $html .= $input;
        if ($group['append'] ?? '') {
            $html .= '<div class="input-group-append">';
            $html .= $group['append'];
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
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
                if (is_object($data) && isset($data->list)) {
                    $parm = array_merge(object_to_array($data), $parm);
                    $data = bomber()->objectClone($data->list);
                }
                // 强制变更为数组
                $data = object_to_array($data);
                $title = $parm['title'] ?? '';
                $bind = $parm['bind'] ?? '';
                foreach ($data as $key => $val) {
                    // 如果是对象处理
                    if (is_object($data)) {
                        // 如果是一维对象的话，则直接转换为数组
                        if (!is_object($val))
                            $newData[$title ? $val : $key] = $val;
                        // 如果是二维对象的话，转成数组，值由指定的成员名来获取
                        else if ($title && is_object($val)) {
                            if ($bind)
                                $newData[$val->{$bind}] = $val->{$title};
                            else
                                $newData[$key] = $val->{$title};
                        }
                    }
                    // 如果是数组处理
                    else if (is_array($data)) {
                        // 如果是一维数组的话，则直接保留结构
                        if (bomber()->arrayLevel($data) == 1)
                            $newData[$title ? $val : $key] = $val;
                        // 如果是二维数组的话，和对象相同的处理
                        else if ($title && bomber()->arrayLevel($data) >= 2) {
                            if ($bind)
                                $newData[$val[$bind]] = $val[$title];
                            else
                                $newData[$key] = $val[$title];
                        }
                    }
                }
                break;
        }

        return $newData;
    }
}
