<?php

return [
    //  网页相关
    'web' => [
        //  标题
        'title' => env('ADMIN_WEB_TITLE', 'Admin Management'),
        //  入口
        'path' => 'admin',
        //  配置CDN后一些资源将加载CDN，否则走本地
        'cdnUrl' => 'https://cdn.bootcdn.net/ajax/libs/',
        //  风格
        'style' => [
            //  布局（horizontal/vertical）
            'layout' => 'vertical',
            //  主题（light/dark）
            'theme' => 'light'
        ],
    ],
    //  表格相关
    'dbtable' => [
        'id' => 'dbTable',
        'namespace' => '$.admin.table',
        'template' => [
            'script' => 'admin::preset.dbtable.script',
            'search' => 'admin::preset.dbtable.search',
            'button' => 'admin::preset.dbtable.button',
        ],
        'locale' => 'zh-CN',
        'totalField' => 'total',
        'classes' => 'table table-bordered table-hover table-nowrap table-striped',
        'batch' => true,
        'method' => 'post',
        'cache' => false,
        'dataField' => 'list',
        'sortable' => true,
        'clickToSelect' => true,
        'pagination' => true,
        'sidePagination' => 'server',
        'pageSize' => 15,
        'pageNumber' => 1,
        'pageList' => [15, 30, 50, 100],
        'paginationLoop' => false,
        'showJumpTo' => true,
        'queryParamsType' => 'limit',
        'search' => true,
        'searchOnEnterKey' => true,
        'searchPanel' => true,
        'searchPanelOpen' => true,
        'searchTemplate' => 'dbSearchTemplate',
        'loadingFontSize' => 20,
        'ajax' => '$.admin.table.ajax',
        'actionName' => '_action',
        'actionEvent' => 'action',
        'searchForm' => 'dbSearchForm',
        'searchList' => 'searchs',
        'showColumns' => true,
        'showColumnsToggleAll' => true,
        'showColumnsSearch' => true,
        'minimumCountColumns' => 2,
        'showRefresh' => true,
        'showToggle' => false,
        'showFullscreen' => true,
        'idField' => 'id',
        'selectItemName' => 'selects',
        'toolbar' => '#toolbar',
        'toolbarButton' => 'btn btn-primary',
    ],
];
