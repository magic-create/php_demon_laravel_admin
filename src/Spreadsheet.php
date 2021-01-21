<?php
/**
 * 本文件用于定义一些导出相关的内容
 * Created by M-Create.Team,
 * Copyright 魔网天创信息科技
 * User: ComingDemon
 * Date: 2020/12/112
 * Time: 20:40
 */

namespace Demon\AdminLaravel;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheets;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Spreadsheet
{
    /**
     * @var object 对象实例
     */
    protected static $instance;
    /**
     * @var array 配置
     */
    protected $_config = [];
    /**
     * @var array 数据
     */
    protected $_data = [];

    /**
     * 初始化
     *
     * @return object|static
     *
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
     * Export constructor.
     *
     * @param array $parm
     */
    public function __construct($parm = [])
    {
        $this->_config = $parm;
    }

    /**
     * 便捷处理字段
     *
     * @param array $list
     *
     * @return array[]
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function column($list = [])
    {
        $data = ['title' => [], 'width' => [], 'format' => []];
        foreach ($list as $key => $val) {
            //  可以简写
            if (!is_array($val)) {
                $val = [$val];
            }
            //  如果键非数字
            if (!is_numeric($key)) {
                if (count($val) == 1) {
                    if (is_numeric($val[0]))
                        array_push($val, 'string');
                    else
                        array_unshift($val, 20);
                }
                array_unshift($val, $key);
            }
            $data['title'][] = $val[0] ?? '';
            $data['width'][] = $val[1] ?? ($val['width'] ?? 20);
            $data['format'][] = $val[2] ?? ($val['format'] ?? 'string');
        }

        return $data;
    }

    /**
     * 设置配置
     *
     * @param array $config
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function config($config = [])
    {
        //  如果需要自动处理
        if ($config['column'] ?? [])
            $config = array_merge(self::column($config['column']), $config);
        $this->_config = array_merge($this->_config, $config);
        //  表格信息
        $this->_config['sheet'] = $this->_config['sheet'] ?? 'Sheet1';
        $this->_config['property'] = $this->_config['sheet'] ?? [];
        $this->_config['title'] = $this->_config['title'] ?? [];
        $this->_config['height'] = $this->_config['height'] ?? 20;
        $this->_config['width'] = $this->_config['width'] ?? [];
        $this->_config['format'] = $this->_config['format'] ?? [];
        $this->_config['cache'] = $this->_config['cache'] ?? storage_path('admin/sheet/cache');

        return $this;
    }

    /**
     * 设置内容
     *
     * @param $data
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function data($data)
    {
        $this->_data = $data;

        return $this;
    }

    /**
     * 将数字索引转换为表格索引
     *
     * @param int $index
     *
     * @return float|int|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function index($index = 0)
    {
        if (is_numeric($index)) {
            if ($index < 26)
                $string = chr(65 + $index);
            else if ($index < 702)
                $string = chr(64 + ($index / 26)) . chr(65 + $index % 26);
            else
                $string = chr(64 + (($index - 26) / 676)) . chr(65 + ((($index - 26) % 676) / 26)) . chr(65 + $index % 26);
        }
        else {
            $string = 0;
            $list = str_split($index);
            foreach ($list as $key => $val)
                $string = $string * 26 + ord($val) - 65 + 1;
        }

        return $string;
    }

    /**
     * 导出表格
     *
     * @return Spreadsheets
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function export()
    {
        //  获取配置
        $this->config();
        //  创建一个新的表格
        $sheet = new Spreadsheets();
        //  定义文档属性
        $sheet->getProperties()
              ->setCreator($this->_config['property']['user'] ?? 'sheet')
              ->setLastModifiedBy($this->_config['property']['user'] ?? 'sheet')
              ->setTitle($this->_config['property']['title'] ?? 'Sheet1')
              ->setSubject($this->_config['property']['subject'] ?? 'sheet')
              ->setDescription($this->_config['property']['desc'] ?? 'sheet')
              ->setKeywords($this->_config['property']['keywords'] ?? 'sheet')
              ->setCategory($this->_config['property']['user'] ?? 'sheet')
              ->setCompany($this->_config['property']['user'] ?? 'sheet');
        //  获取当前操作对象
        $object = $sheet->getActiveSheet();
        //  设置文档标题
        $object->setTitle($this->_config['sheet']);
        //  设置默认行高
        if ($this->_config['height'])
            $object->getDefaultRowDimension()->setRowHeight($this->_config['height']);
        //  设置表格头部
        foreach ($this->_config['title'] as $key => $val) {
            $skey = $this->index($key);
            $skeyName = $skey . '1';
            //  设置宽度
            $width = ($this->_config['width'][$key] ?? 0);
            if ($width)
                $object->getColumnDimension($skey)->setWidth($width * 72 / 96);
            else
                $object->getColumnDimension($skey)->getAutoSize();
            //  设置第一栏的标题
            $object->setCellValue($skeyName, $val);
            //  设置第一栏的字体
            $object->getStyle($skeyName)->getFont()->setBold(true)->setSize(12);
            //  设置第一栏的高度
            $object->getRowDimension(1)->setRowHeight(24);
            $object->getStyle($skeyName)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            //  设置第一栏的属性
            $format = strtolower($this->_config['format'][$key] ?? 'string');
            switch ($format) {
                case 'int':
                    $object->getStyle($skeyName)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    break;
                case 'float':
                case 'double':
                    $object->getStyle($skeyName)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    break;
                case 'time':
                    $object->getStyle($skeyName)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);
                    break;
                default:
                case 'string':
                    $object->getStyle($skeyName)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    break;
            }
        }
        $images = [];
        //  设置表格内容
        foreach ($this->_data as $key => $val) {
            foreach ($this->_config['title'] as $k => $v) {
                $skey = $this->index($k);
                $skeyName = $skey . ($key + 2);
                //  设置属性
                $format = strtolower($this->_config['format'][$k] ?? 'string');
                //  设置内容
                $value = $val[$k];
                //  数字转换
                if ((is_numeric($value) || is_double($value) || is_float($value)) && $value >= 1e10)
                    $value = (string)$value;
                $object->setCellValue($skeyName, $value);
                //  URL转换
                if ($format == 'url') {
                    $object->getCell($skeyName)->getHyperlink()->setUrl($value);
                    $object->getCell($skeyName)->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
                }
                //  图片渲染
                if ($format == 'image' && $value) {
                    //  将斜杠换成下滑线方式出现路径问题
                    $cache = str_replace(['?', ':', ',', '╲', '/', '*', "'", '"', '<', '>', '|'], '_', $value);
                    //  下载图片
                    if (!isset($images[$cache])) {
                        $file = $this->_config['cache'] . '/' . $cache;
                        if (!is_file($file)) {
                            try {
                                $content = file_get_contents($value);
                                if ($content) {
                                    bomber()->fileCreate($cache, $content, $this->_config['cache']);
                                    $images[$cache] = $file;
                                }
                                else
                                    $images[$cache] = '';
                            } catch (Exception $e) {
                                $images[$cache] = '';
                            }
                        }
                        else
                            $images[$cache] = $file;
                    }
                    $object->setCellValue($skeyName, '');
                    if ($images[$cache]) {
                        $drawing = (new Drawing());
                        $drawing
                            ->setCoordinates($skeyName)
                            ->setResizeProportional(false)
                            ->setWidth((max(0, $object->getColumnDimension($skey)->getWidth()) ? : 20) * 96 * 2 / 72)
                            ->setHeight($this->_config['height'])
                            ->setOffsetX(($this->_config['width'][$k] ?? 20) * 0.1)
                            ->setOffsetY(($this->_config['height'] ?? 20) * 0.1)
                            ->setWorksheet($object)
                            ->setPath($images[$cache]);
                    }
                }
                //  设置左对齐
                $object->getStyle($skeyName)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
            }
        }

        //  返回对象
        return $sheet;
    }

    /**
     * 快速下载
     *
     * @param $name
     * @param $config
     * @param $data
     *
     * @return bool|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function download($name, $config, $data)
    {
        return $this->save($this->config($config)->data($data)->export(), $name, 'download');
    }

    /**
     * 下载或保存
     *
     * @param Spreadsheets $sheet
     * @param string       $filename
     * @param string       $type
     *
     * @return bool|string
     *
     * @copyright 魔网天创信息科技
     * @author    ComingDemon
     */
    public function save(Spreadsheets $sheet, $filename = '', $type = 'download')
    {
        //  获取文件格式
        $format = 'xls';
        $pathInfo = pathinfo($filename);
        if ($pathInfo && isset($pathInfo['extension']))
            $format = strtolower($pathInfo['extension']);
        //  保存方式
        switch ($type) {
            //  下载
            case 'download':
                //  通过格式来决定方法
                switch ($format) {
                    case 'xlsx':
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        break;
                    case 'xls':
                        header('Content-Type: application/vnd.ms-excel');
                        break;
                    case 'csv':
                        header('Content-Type: text/csv');
                        break;
                }
                //  定义下载
                header("Content-Disposition: attachment;filename={$filename}");
                header('Cache-Control: max-age=0');
                //  开始写出
                $writer = IOFactory::createWriter($sheet, ucfirst($format));
                //  保存到接口
                $writer->save('php://output');

                return $filename;
                break;
            //  保存
            case 'local':
                //  开始写出
                $writer = IOFactory::createWriter($sheet, ucfirst($format));
                //  创建目录
                bomber()->dirMake(dirname($filename));
                //  保存到文件
                $writer->save($filename);

                return $filename;
                break;
        }

        //  返回失败
        return false;
    }

    /**
     * 导入表格
     * @return array
     *
     * @copyright 魔网天创信息科技
     *
     * @author    ComingDemon
     */
    public function import()
    {
        //  获取文件格式
        $format = 'xls';
        $pathInfo = pathinfo($this->_data);
        if ($pathInfo && isset($pathInfo['extension']))
            $format = strtolower($pathInfo['extension']);
        $format = ucfirst($format);
        $reader = IOFactory::createReader($format);
        $object = $reader->load($this->_data);
        $count = [
            'x' => $this->_config['title'] ? count($this->_config['title']) : (int)$this->index($object->getSheet(0)->getHighestColumn()),
            'y' => (int)$object->getSheet(0)->getHighestRow()
        ];
        $sheet = $object->getActiveSheet();
        //  获取标题列
        $title = $this->_config['title'] ? : [];
        if (!$title) {
            for ($i = 0; $i < $count['x']; $i++) {
                $skey = $this->index($i);
                $skeyName = $skey . '1';
                $title[] = $sheet->getCell($skeyName)->getValue();
            }
        }
        $data = [];
        $array = $sheet->toArray();
        foreach ($array as $key => $val) {
            //  跳出标题
            if (!$key && $this->_config['title'] || (!$key && !$this->_config['title']))
                continue;
            //  循环复制
            $foo = [];
            foreach ($title as $k => $v) {
                $foo[$v] = trim($val[$k]);
            }
            $data[] = $foo;
        }

        //  返回数组
        return $data;
    }
}
