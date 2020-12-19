<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta name="applicable-device" content="pc,mobile">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="theme-color" content="#ec7259">
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="google" content="notranslate">
    <title>@yield('title',config('admin.web.title'))</title>
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">
    <link rel="shortcut icon" href="{{$view->asset}}/images/favicon.ico">
    {{--加载脚本--}}
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/node-waves/0.7.6/waves.min.js"></script>
    {{--启动应用--}}
    <script src="{{$view->asset}}/js/app.js"></script>
    {{--加载脚本--}}
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/layui/2.5.7/layui.all.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/echarts/4.8.0/echarts.min.js"></script>
    {{--加载样式--}}
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.bootcdn.net/ajax/libs/metisMenu/2.7.9/metisMenu.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.bootcdn.net/ajax/libs/node-waves/0.7.6/waves.min.css" rel="stylesheet" type="text/css">
    <link href="/static/library/layui/css/layui.css" rel="stylesheet" type="text/css">
    <link href="{{$view->asset}}/css/style.css" rel="stylesheet" type="text/css">
    <link href="{{$view->asset}}/css/layout_{{config('admin.web.style.layout')}}.css" rel="stylesheet" type="text/css">
    {{--挂载引入区域--}}
    @yield('link')
    <script>
        window.$ = window.jQuery = layui.$;
        window._token = '{{function_exists('csrf_token') ? csrf_token() : ''}}';
    </script>
    {{--挂载样式区域--}}
    @yield('style')
</head>
<body>
{{--布局区域--}}
@yield('frame')
{{--挂载脚本区域--}}
@yield('script')
</body>
</html>
