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
    <link rel="shortcut icon" href="/static/admin/images/favicon.ico">
    @php($assetUrl = config('admin.web.cdnUrl') ?: '/static/admin/libs')
    {{--加载脚本--}}
    <script src="{{$assetUrl}}/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{$assetUrl}}/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="{{$assetUrl}}/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <script src="{{$assetUrl}}/node-waves/0.7.6/waves.min.js"></script>
    <script src="{{$assetUrl}}/select2/4.0.9/js/select2.full.min.js"></script>
    <script src="{{$assetUrl}}/iCheck/1.0.3/icheck.min.js"></script>
    <script src="{{$assetUrl}}/bootstrap-switch/4.0.0-alpha.1/js/bootstrap-switch.min.js"></script>
    <script src="{{$assetUrl}}/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>
    <script src="{{$assetUrl}}/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js"></script>
    <script src="{{$assetUrl}}/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script src="{{$assetUrl}}/marked/1.2.7/marked.min.js"></script>
    <script src="{{$assetUrl}}/moment.js/2.29.1/moment.min.js"></script>
    <script src="/static/admin/libs/bootstrap4-datetimepicker/4.17.50/js/bootstrap-datetimepicker.js"></script>
    <script src="{{$assetUrl}}/jquery-validate/1.9.0/jquery.validate.min.js"></script>
    <script src="{{$assetUrl}}/bootstrap-table/1.18.1/bootstrap-table.min.js"></script>
    <script src="{{$assetUrl}}/bootstrap-table/1.18.1/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
    <script src="/static/admin/libs/bootstrap4-layer/3.1.1/layer.min.js"></script>
    {{--启动应用--}}
    <script src="/static/admin/js/app.js"></script>
    <script src="/static/admin/js/demon.js"></script>
    <script src="/static/admin/js/lang/zh-CN.js"></script>
    {{--加载脚本--}}
    <script src="{{$assetUrl}}/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
    {{--加载样式--}}
    <link href="{{$assetUrl}}/twitter-bootstrap/4.5.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/metisMenu/2.7.9/metisMenu.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/node-waves/0.7.6/waves.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/select2/4.0.9/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/iCheck/1.0.3/skins/all.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="{{$assetUrl}}/bootstrap-table/1.18.1/bootstrap-table.min.css" rel="stylesheet" type="text/css">
    <link href="/static/admin/css/style.css" rel="stylesheet" type="text/css">
    <link href="/static/admin/css/layout_{{config('admin.web.style.layout')}}.css" rel="stylesheet" type="text/css">
    {{--挂载引入区域--}}
    @yield('link')
    <script>
        window._token = '{{function_exists('csrf_token') ? csrf_token() : ''}}';
        $.ajaxSetup({headers:{'X-CSRF-TOKEN':window._token}});
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
