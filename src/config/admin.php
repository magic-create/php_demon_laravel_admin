<?php

return [
    //  标题
    'title' => env('ADMIN_TITLE', 'Admin Management'),
    //  自定义路由入口
    'path' => env('ADMIN_PATH', 'admin'),
    //  配置CDN后一些资源将加载CDN，否则走本地
    'cdn' => env('ADMIN_CDN', 'https://cdn.bootcdn.net/ajax/libs/'),
    //  静态资源释放目录（位于public目录下）
    'static' => env('ADMIN_STATIC', '/static/admin'),
    //  布局（horizontal/vertical）
    'layout' => env('ADMIN_LAYOUT', 'vertical'),
    //  主题（light/dark）
    'theme' => env('ADMIN_THEME', 'light'),
    //  背景图片
    'background' => [
        //  切换模式（random/daily）
        'mode' => env('ADMIN_BACKGROUND_MODE', 'random'),
        //  自定义图片列表
        'list' => explode(',', env('ADMIN_BACKGROUND_LIST', ''))
    ],
    //  验证码
    'captcha' => [
        //  字数
        'length' => env('ADMIN_CAPTCHA_LENGTH', 4),
        //  字符内容
        'charset' => env('ADMIN_CAPTCHA_CHARSET', '0123456789'),
        //  宽度
        'width' => env('ADMIN_CAPTCHA_WIDTH', 192),
        //  高度
        'height' => env('ADMIN_CAPTCHA_HEIGHT', 64),
    ]
];
