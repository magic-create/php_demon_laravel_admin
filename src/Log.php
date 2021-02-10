<?php

namespace Demon\AdminLaravel;

/**
 * Class Log
 * @package Demon\AdminLaravel
 */
class Log
{
    /**
     * @var int
     */
    public $uid;

    /**
     * @var string|array
     */
    public $tag;

    /**
     * @var string|array
     */
    public $content;

    /**
     * @var string
     */
    public $remark;

    /**
     * @var bool
     */
    public $break = false;

    /**
     * Log constructor.
     */
    public function __construct(int $uid = 0)
    {
        $this->setUid($uid);
    }

    /**
     * 是否阻止
     *
     * @param bool $break
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setBreak(bool $break)
    {
        $this->break = $break;

        return $this;
    }

    /**
     * 设置用户
     *
     * @param string $uid
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setUid(int $uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * 设置标记
     *
     * @param string $tag
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * 设置内容
     *
     * @param string $content
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * 设置备注
     *
     * @param string $remark
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setRemark(string $remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * 过滤密码和过长的字段
     *
     * @param $data
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function filter($data)
    {
        foreach ($data as $name => $value) {
            if (!is_array($value)) {
                if (is_string($value) && mb_strlen($value) > 256 || mb_stripos($name, 'password') !== false || $value === '')
                    unset($data[$name]);
            }
            else
                $data[$name] = self::filter($value);
        }

        return $data;
    }

    /**
     * 生成数据结构
     *
     * @param null $redact
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function build($redact = null)
    {
        //  获取当前内容
        if (app()->runningInConsole()) {
            $method = 'COMMAND';
            $path = request()->server('SCRIPT_NAME');
            $arguments = [];
            foreach (request()->server('argv') as $argv) {
                if ($argv != $path)
                    $arguments[] = $argv;
            }
        }
        else {
            $method = request()->method();
            $path = admin_url_repre(request()->url() ?? null);
            $arguments = request()->all();
        }

        //  组合内容
        $data = [
            'uid' => $this->uid,
            'tag' => $this->tag,
            'method' => $method,
            'path' => $path,
            'remark' => $this->remark,
            'content' => $this->content,
            'arguments' => $arguments,
            'ip' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'createTime' => mstime(),
            'createDate' => date('Ymd'),
            'soleCode' => DEMON_SOLECODE
        ];

        //  自定义编辑
        if ($redact && bomber()->isFunction($redact))
            $redact($data);

        //  记录内容
        return [
            'uid' => $data['uid'],
            'tag' => (is_array($data['tag']) ? implode('.', $data['tag']) : $data['tag']) ? : null,
            'method' => mb_substr(strtoupper($data['method']), 0, 32) ? : null,
            'path' => mb_substr($data['path'], 0, 255) ? : null,
            'remark' => $data['remark'] ? : null,
            'content' => $data['content'] ? (is_string($data['content']) ? $data['content'] : json_encode($data['content'], JSON_UNESCAPED_UNICODE)) : null,
            'arguments' => $data['arguments'] ? (is_string($data['arguments']) ? $data['arguments'] : json_encode($data['arguments'], JSON_UNESCAPED_UNICODE)) : null,
            'ip' => is_numeric($data['ip']) ? $data['ip'] : ip2long($data['ip']),
            'userAgent' => mb_substr($data['userAgent'], 0, 255),
            'createTime' => $data['createTime'],
            'createDate' => $data['createDate'],
            'soleCode' => $data['soleCode']
        ];
    }
}
