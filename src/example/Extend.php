<?php

namespace Demon\AdminLaravel\example;

use Demon\AdminLaravel\Controller as Controllers;

class Extend extends Controllers
{
    protected $loginExcept = ['captcha'];

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证码
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function captcha()
    {
        return response(app('admin')->captcha([])->output(), 200, ['Content-Type' => 'image/jpeg']);
    }

    /**
     * 外链获取
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function dump()
    {
        //  获取参数
        $url = request()->get('url');
        if (!$url || filter_var($url, FILTER_VALIDATE_URL) === false)
            return response(null, DEMON_CODE_NONE);
        //  Curl请求
        bomber()->curl($url, [], ['method' => 'file'], function($result, $status) use (&$content, &$type) {
            $content = ($status['http_code'] ?? 0) == 200 ? $result : null;
            $type = $status['content_type'];
        });

        //  响应结果
        return response($content, $type ? 200 : DEMON_CODE_NONE, ['Content-Type' => $type]);
    }

    /**
     * 图片上传
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function upload()
    {
        if (!$_FILES)
            abort(DEMON_CODE_PARAM);
        $dir = arguer('dir', 'upload/admin/example/' . date('Ymd'));
        $path = public_path($dir);
        //  如果目录不存在则创建
        if (!is_dir($path))
            bomber()->dirMake($dir);
        //  将文件移动到这里
        $response = [];
        foreach ($_FILES as $key => $file) {
            $name = $file['name'] . '.' . sha1(mstime() . $file['name']) . '.' . str_replace('image/', '', $file['type']);
            $response[$key] = move_uploaded_file($file['tmp_name'], $path . '/' . $name) ? url($dir . '/' . $name) : false;
        }

        return response($response);
    }
}
