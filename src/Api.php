<?php

namespace Demon\AdminLaravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Api
 * @package Demon\AdminLaravel
 */
class Api
{
    use Macroable;

    /**
     * @var int 状态码
     */
    public $code = 0;

    /**
     * @var string 信息内容
     */
    public $message = 'success';

    /**
     * @var array 数据
     */
    public $data = [];

    /**
     * @var int 默认错误码
     */
    public $default = DEMON_CODE_PARAM;

    /**
     * @var int JSON选项
     */
    public $options = 0;

    /**
     * @var string[] 错误提示
     */
    public $preset = [
        DEMON_CODE_PARAM,
        DEMON_CODE_AUTH,
        DEMON_CODE_FORBID,
        DEMON_CODE_NONE,
        DEMON_CODE_ACCESS,
        DEMON_CODE_FAIL,
        DEMON_CODE_TIME,
        DEMON_CODE_COND,
        DEMON_CODE_LARGE,
        DEMON_CODE_MEDIA,
        DEMON_CODE_EXPIRED,
        DEMON_CODE_MANY,
        DEMON_CODE_SERVER,
        DEMON_CODE_DATA,
        DEMON_CODE_SERVICE
    ];

    /**
     * Api constructor.
     *
     * @param int   $default
     * @param array $message
     */
    public function __construct($default = DEMON_CODE_PARAM, $message = [])
    {
        $this->preset = $message + array_flip($this->preset);
        foreach (array_keys($this->preset) as $code)
            $this->preset[$code] = $message[$code] ?? admin_error($code);
        $this->default = $default;
    }

    /**
     * 检查参数合法性
     *
     * @param array $list
     * @param array $rules
     * @param array $messages
     * @param array $origin
     *
     * @return array|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function validator(array $list = [], array $rules = [], array $messages = [], $origin = [])
    {
        //  定义内容
        $origin = $origin ? : arguer();
        //  循环合并规则
        $field = [];
        foreach ($list as $key => $val) {
            if (is_string($val)) {
                $rules[$key] = 'required';
                $messages[$key . '.*'] = $val;
            }
            if (is_array($val) && ($val['name'] ?? ''))
                $field[$key] = $val['name'];
            if (is_array($val) && ($val['rule'] ?? []))
                $rules[$key] = $val['rule'];
            if (is_array($val) && ($val['message'] ?? [])) {
                if (is_array($val['message']))
                    foreach ($val['message'] as $k => $v) {
                        $messages[$key . '.' . $k] = $v;
                    }
                else $messages[$key . '.*'] = $val['message'];
            }
        }
        //  开始验证
        $validator = Validator::make($origin, $rules, $messages, $field);
        if ($validator->fails()) {
            //  定义错误消息
            $message = '';
            //  循环错误内容并取出第一条错误
            foreach (json_decode(json_encode($validator->errors()), 1) as $error) {
                $message = $error[0];
                break;
            }

            //  返回错误异常
            return error_build($this->default, $message);
        }

        //  返回全部参数
        return $origin;
    }

    /**
     * 检查请求参数并抛出异常错误
     *
     * @param array $list
     * @param array $rules
     * @param array $messages
     *
     * @return array|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function arguer(array $list = [], array $rules = [], array $messages = [])
    {
        //  检查请求参数
        $data = self::validator($list, $rules, $messages, arguer());
        if (!error_check($data))
            self::check($data);

        //  返回全部参数
        return $data;
    }

    /**
     * 检查错误并响应
     *
     * @param       $content
     * @param array $extend
     *
     * @return bool|int|mixed|string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function check($content, array $extend = [])
    {
        return error_check($content, function($error) use ($extend) { abort($this->setError($error)->send($extend)); });
    }

    /**
     * 设置JSON选项
     *
     * @param int $options
     *
     * @return $this
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setOptions($options = 0)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * 构建错误内容
     *
     * @param $error
     * @param $message
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setError($error, $message = '')
    {
        //  如果是数组则转换为对象
        if (!is_object($error))
            bomber()->errorCheck($error, function($e) use (&$error) { $error = $e; });
        //  设置错误码
        $this->setCode($error->code ?? $this->default);
        //  特殊设置内容
        if ($message)
            $error->message = $message;
        //  设置错误信息
        if ($error->message ?? '')
            $this->setMessage($error->message ?? 'fail');

        return $this;
    }

    /**
     * 快速输出错误
     *
     * @param string $message
     * @param int    $error
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function sendError($message = '', $error = DEMON_CODE_PARAM)
    {
        //  如果第一个参数是数字则表示错误码，参数顺位
        if (is_numeric($message)) {
            $error = $message;
            $message = '';
        }

        return abort($this->setError($error, $message)->send());
    }

    /**
     * 设置返回数据
     *
     * @param mixed ...$data
     *
     * @return $this
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setData(...$data)
    {
        //  获取参数
        $data = func_get_args();
        //  如果没有参数则跳过
        if (isset($data[0])) {
            //  数组合并
            if (is_array($data[0]))
                $this->data = array_merge($this->data, $data[0]);
            //  对方赋值
            else if (is_object($data[0]))
                $this->data = $data[0];
            //  键值设置
            else $this->data[$data[0]] = $data[1];
        }

        return $this;
    }

    /**
     * 快速输出内容
     *
     * @param mixed ...$data
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function sendData(...$data)
    {
        return $this->setData(...$data)->send();
    }

    /**
     * 设置状态码
     *
     * @param $code
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setCode(int $code)
    {
        //  设置状态码
        $this->code = (int)$code;
        //  有状态码则表示错误
        if (!$this->message || $this->message == 'success')
            $this->message = $this->preset[$this->code] ?? 'fail';

        return $this;
    }

    /**
     * 快速输出状态码
     *
     * @param int   $code
     * @param array $extend
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function sendCode(int $code, array $extend = [])
    {
        return $this->setCode($code)->send($extend);
    }

    /**
     * 设置消息内容
     *
     * @param string $message
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setMessage(string $message)
    {
        //  设置消息内容
        $this->message = $message;

        return $this;
    }

    /**
     * 快速输出消息
     *
     * @param string $message
     * @param array  $extend
     *
     * @return string
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function sendMessage(string $message, array $extend = [])
    {
        return $this->setMessage($message)->send($extend);
    }

    /**
     * 返回发送结果
     *
     * @param array $extend
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function send(array $extend = [])
    {
        //  拼接结果
        $result = ['code' => $this->code, 'message' => $this->message];
        if ($this->data)
            $result['data'] = $this->data;
        //  合并扩展内容
        $result = array_merge($result, $extend);

        //  返回结果内容
        return response()->json($result, $this->code ? in_array($this->code, array_keys($this->preset)) ? $this->code : $this->default : 200, [], $this->options);
    }
}
