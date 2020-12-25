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
    //  表格相关
    'datatable' => [
        'render' => [
            'namespace' => 'DemonDataTable',
            'script' => 'admin::preset.datatable.script'
        ],
        'stype' => ['display' => true],
        'dom' => 'Blfrtip',
        'autoWidth' => false,
        'colReorder' => true,
        'responsive' => false,
        'searching' => true,
        'searchTemplate' => 'DemonSearchTemplate',
        'searchForm' => 'DemonSearchForm',
        'ordering' => true,
        'stripeClasses' => ['odd', 'even'],
        'processing' => true,
        'info' => true,
        'serverSide' => true,
        'paging' => true,
        'pagingType' => 'full_numbers',
        'serverParams' => '',
        'pageLength' => 30,
        'lengthMenu' => [10, 30, 50, 100],
        'batchClass' => 'icheckbox_minimal-aero'
    ],
];
