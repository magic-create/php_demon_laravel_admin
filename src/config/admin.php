<?php

return [
    //  标题
    'title' => env('ADMIN_TITLE', 'Admin Management'),
    //  自定义路由入口
    'path' => env('ADMIN_PATH', 'admin'),
    //  配置CDN后一些资源将加载CDN，否则走本地
    'cdn' => env('ADMIN_CDN', 'https://cdn.bootcdn.net/ajax/libs'),
    //  静态资源释放目录（位于public目录下）
    'static' => env('ADMIN_STATIC', '/static/admin'),
    //  布局（horizontal/vertical）
    'layout' => env('ADMIN_LAYOUT', 'vertical'),
    //  主题（light/dark）
    'theme' => env('ADMIN_THEME', 'light'),
    //  是否开启Tabs-Frame（启用时为URL参数标记）
    'tabs' => env('ADMIN_TABS', false),
    //  数据库连接
    'connection' => env('ADMIN_CONNECTION', 'admin'),
    //  权限验证
    'access' => env('ADMIN_ACCESS', false),
    //  授权控制器
    'authentication' => env('ADMIN_AUTHENTICATION', \Demon\AdminLaravel\access\controller\AuthController::class),
    //  菜单统计
    'badge' => env('ADMIN_BADGE', \Demon\AdminLaravel\example\Service::class),
    //  通知内容
    'notification' => env('ADMIN_NOTIFICATION', \Demon\AdminLaravel\example\Service::class),
    //  使用语言
    'locales' => [
        'en' => 'English',
        'zh-CN' => '简体中文',
        'zh-TW' => '繁體中文',
    ],
    //  Session
    'session' => [
        //  驱动
        'driver' => env('ADMIN_SESSION_DRIVER', 'file'),
        //  有效时间
        'lifetime' => env('ADMIN_SESSION_LIFETIME', 120),
        //  不保留
        'expire_on_close' => false,
        //  加密会话
        'encrypt' => false,
        //  保存文件
        'files' => storage_path('framework/admins'),
        //  数据库连接
        'connection' => env('ADMIN_CONNECTION', 'admin'),
        //  数据表名
        'table' => env('ADMIN_SESSION_TABLE', 'admin_session'),
        //  缓存库
        'store' => env('ADMIN_SESSION_STORE', null),
        //  过期会话清除概率
        'lottery' => [2, 100],
        //  Cookie名字
        'cookie' => env('ADMIN_SESSION_COOKIE', 'admin_session'),
        //  Cookie路径
        'path' => '/' . env('ADMIN_PATH', 'admin'),
        //  Cookie域名
        'domain' => env('ADMIN_SESSION_DOMAIN', null),
        //  仅支持HTTPS
        'secure' => env('ADMIN_SESSION_SECURE_COOKIE', false),
        //  仅支持HTTP协议
        'http_only' => true,
        //  跨站保护
        'same_site' => null,
    ],
    //  构建元素模板
    'element' => [
        //  面包屑导航
        'breadcrumb' => env('ADMIN_ELEMENT_BREADCRUMB', 'admin::preset.element.breadcrumb'),
        //  底部
        'footer' => env('ADMIN_ELEMENT_FOOTER', 'admin::preset.element.footer'),
        //  侧边导航
        'slidebar' => env('ADMIN_ELEMENT_SLIDEBAR', 'admin::preset.element.slidebar'),
        //  顶部导航
        'topbar' => env('ADMIN_ELEMENT_TOPBAR', 'admin::preset.element.topbar')
    ],
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
