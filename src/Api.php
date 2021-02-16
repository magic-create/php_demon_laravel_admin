<?php

namespace Demon\AdminLaravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class Api
 * @package Demon\AdminLaravel
 */
class Api
{
    /**
     * @var int 状态码
     */
    private $code = 0;

    /**
     * @var string 信息内容
     */
    private $message = 'success';

    /**
     * @var array 数据
     */
    private $data = [];

    /**
     * @var string[] 错误提示
     */
    private $default = [
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
     * @param array $message
     */
    public function __construct($message = [])
    {
        $this->default = $message + array_flip($this->default);
        foreach (array_keys($this->default) as $code)
            $this->default[$code] = $message[$code] ?? admin_error($code);
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
            return error_build(DEMON_CODE_PARAM, $message);
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
        $this->setCode($error->code ?? DEMON_CODE_PARAM);
        //  特殊设置内容
        if ($message)
            $error->message = $message;
        //  设置错误信息
        if ($error->message ?? '')
            $this->setMessage($error->message ?? 'fail');

        return $this;
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
        if (isset($data[0]))
            //  如果第二个值未设定则表示是数组直接设定，否则为键值对设定
            is_array($data[0]) || is_object($data[0]) ? $this->data = $data[0] : $this->data[$data[0]] = $data[1];

        return $this;
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
            $this->message = $this->default[$this->code] ?? 'fail';

        return $this;
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
        return response()->json($result, $this->code ? in_array($this->code, array_keys($this->default)) ? $this->code : DEMON_CODE_PARAM : 200, [], JSON_UNESCAPED_UNICODE);
    }
}
