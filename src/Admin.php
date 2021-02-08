<?php
/**
 * 本文件用于定义一些运维后台相关的内容
 * Created by M-Create.Team
 * Copyright 魔网天创信息科技
 * User: ComingDemon
 * Date: 2020/12/19
 * Time: 23:15
 */

namespace Demon\AdminLaravel;

use Demon\AdminLaravel\access\Service;
use Exception;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Config\Repository;

class Admin
{
    /**
     * @var array|mixed
     */
    protected $config;

    /**
     * @var Service
     */
    public $access;

    /**
     * @var Object
     */
    public $user;

    /**
     * Admin constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('admin');
        $this->access = new Service();
    }

    /**
     * 设置用户UID
     *
     * @param int $uid
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setUid(int $uid = 0)
    {
        $this->access->uid = $uid;

        return $this;
    }

    /**
     * 获取用户UID
     *
     * @return int
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUid()
    {
        return $this->access->uid;
    }

    /**
     * 设置用户信息
     *
     * @param $user
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * 获取用户信息
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 设置统计内容
     *
     * @param array $badge
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setBadge(array $badge = [])
    {
        $this->access->badge = $badge;

        return $this;
    }

    /**
     * 设置面包屑导航
     *
     * @param array $breadcrumb
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setBreadcrumb(array $breadcrumb = [])
    {
        $this->access->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * 设置通知内容
     *
     * @param array $notification
     *
     * @return $this
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function setNotification(array $notification = [])
    {
        $this->access->notification = $notification;

        return $this;
    }

    /**
     * 自动识别__开头的字符串获取翻译
     *
     * @param       $key
     * @param array $replace
     * @param null  $locale
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function __($key, $replace = [], $locale = null)
    {
        if (mb_stripos($key, '__') === 0) {
            $name = 'admin::' . mb_substr($key, 2);
            $trans = __($name, $replace, $locale);

            return $trans == $name ? mb_substr($key, 2) : $trans;
        }
        else {
            $name = 'admin::' . $key;
            $trans = __($name, $replace, $locale);

            return $trans == $name ? $key : $trans;
        }
    }

    /**
     * 获取用户头像内容
     *
     * @param        $avatar
     * @param string $nickname
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserAvatar($avatar, $nickname = '')
    {
        return $avatar ? $avatar : bomber()->strImage($nickname, 'svg', ['calc' => true, 'substr' => true]);
    }

    /**
     * 获取背景图片
     *
     * @param null $call
     *
     * @return mixed|null
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getBackgroundImage($call = null)
    {
        //  获取配置
        $config = $this->config['background'];
        $mode = $config['mode'];
        $list = $config['list'];
        //  未配置任何内容
        if (!$call && !$list)
            return null;
        //  获取内容
        try {
            //  指定方法
            if ($call && bomber()->isFunction($call))
                return call_user_func($call, $config);
            //  从数组中获取
            if ($list)
                return $list[$config['mode'] == 'random' ? mt_rand(0, count($list) - 1) : date('Ymd') % count($list)];

            //  都没有值
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * 获取用户的导航代
     *
     * @return string
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getUserMenuHtml()
    {
        return $this->access->getUserMenuHtml();
    }

    /**
     * 获取面包屑导航
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getBreadcrumb()
    {
        return $this->access->getBreadcrumb();
    }

    /**
     * 获取通知内容
     *
     * @return array
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function getNotification()
    {
        return $this->access->getNotification();
    }

    /**
     * 生成或读取图形验证码
     *
     * @param mixed $parm
     *
     * @return CaptchaBuilder|string|bool
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function captcha($parm = null)
    {
        //  如果是数组
        if (is_array($parm)) {
            //  拼接默认参数
            $parm += [
                'length' => config('admin.captcha.length'),
                'charset' => config('admin.captcha.charset'),
                'width' => config('admin.captcha.width'),
                'height' => config('admin.captcha.height'),
            ];
            $phraseBuilder = new PhraseBuilder($parm['length'], $parm['charset']);
            $builder = new CaptchaBuilder(null, $phraseBuilder);
            $builder->build($parm['width'], $parm['height']);
            //  保存到Session中
            session(['admin.captcha' => $builder->getPhrase()]);

            //  返回生成器
            return $builder;
        }

        //  如果不是数组则表示对比或者直接返回验证码
        return $parm !== null ? $parm == session('admin.captcha') : session('admin.captcha');
    }
}
