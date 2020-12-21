<?php

return [
    //  网页相关
    'web' => [
        //  标题
        'title' => env('ADMIN_WEB_TITLE', 'Admin Management'),
        //  入口
        'path' => 'admin',
        //  配置CDN后一些资源将加载CDN，否则走本机
        'cdnUrl' => 'https://cdn.bootcdn.net/ajax/libs/',
        //  风格
        'style' => [
            //  布局（horizontal/vertical）
            'layout' => 'vertical'
        ],
    ],
];
