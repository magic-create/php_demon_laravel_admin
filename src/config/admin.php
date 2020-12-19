<?php

return [
    //  网页相关
    'web' => [
        //  标题
        'title' => env('ADMIN_WEB_TITLE', 'Admin Management'),
        //  入口
        'path' => 'admin',
        //  风格
        'style' => [
            //  布局（horizontal/vertical）
            'layout' => 'vertical'
        ],
    ],
];
