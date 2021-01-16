<?php

return [
    //  标题
    'title' => env('ADMIN_TITLE', 'Admin Management'),
    //  自定义路由入口
    'route' => env('ADMIN_ROUTE', 'admin'),
    //  配置CDN后一些资源将加载CDN，否则走本地
    'cdn' => env('ADMIN_CDN', 'https://cdn.bootcdn.net/ajax/libs/'),
    //  静态资源释放目录（位于public目录下）
    'static' => env('ADMIN_STATIC', '/static/admin'),
    //  布局（horizontal/vertical）
    'layout' => env('ADMIN_LAYOUT', 'vertical'),
    //  主题（light/dark）
    'theme' => env('ADMIN_THEME', 'light')
];
