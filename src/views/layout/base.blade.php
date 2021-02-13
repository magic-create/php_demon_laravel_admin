<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" layout="{{config('admin.layout')}}" theme="{{config('admin.theme')}}" {{admin_tabs() ? 'tabs' : ''}}>
<head>
    <meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=5.0,minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta name="applicable-device" content="pc,mobile">
    <meta name="theme-color" content="#2b3a4a">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="google" content="notranslate">
    <title>@yield('title',config('admin.title'))</title>
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">
    <link rel="shortcut icon" href="{{admin_static('images/favicon.ico')}}">
    {{--PHP配置给JS--}}
    <script>
        window._debug = Boolean({!!config('app.debug')!!});
        window._locale = '{{app()->getLocale()}}';
        window._layout = '{{config('admin.layout')}}';
        window._theme = '{{config('admin.theme')}}';
        window._token = '{{function_exists('csrf_token') ? csrf_token() : ''}}';
    </script>
    {{--加载脚本--}}
    <script src="{{admin_cdn('jquery/3.5.1/jquery.min.js')}}"></script>
    <script src="{{admin_cdn('metisMenu/3.0.6/metisMenu.min.js')}}"></script>
    <script src="{{admin_cdn('jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js')}}"></script>
    <script src="{{admin_cdn('node-waves/0.7.6/waves.min.js')}}"></script>
    <script src="{{admin_cdn('select2/4.0.9/js/select2.full.min.js')}}"></script>
    <script src="{{admin_cdn('iCheck/1.0.3/icheck.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-switch/4.0.0-alpha.1/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js')}}"></script>
    <script src="{{admin_cdn('ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{admin_cdn('marked/1.2.7/marked.min.js')}}"></script>
    <script src="{{admin_cdn('moment.js/2.29.1/moment.min.js')}}"></script>
    <script src="{{admin_static('libs/bootstrap4-datetimepicker/4.17.50/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{admin_cdn('jquery-validate/1.9.0/jquery.validate.min.js')}}"></script>
    <script src="{{admin_cdn('cropperjs/1.5.9/cropper.min.js')}}"></script>
    <script src="{{admin_cdn('clipboard.js/2.0.6/clipboard.min.js')}}"></script>
    <script src="{{admin_static('libs/bootstrap4-layer/3.1.1/layer.min.js')}}"></script>
    {{--前置引入区域--}}
    @yield('link.before')
    {{--启动应用--}}
    <script src="{{admin_static('js/app.js')}}"></script>
    <script src="{{admin_static('js/demon.min.js')}}"></script>
    <script src="{{admin_static('js/lang/'.app()->getLocale().'.js')}}"></script>
    {{--加载脚本--}}
    <script src="{{admin_cdn('twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js')}}"></script>
    {{--加载样式--}}
    <link href="{{admin_cdn('twitter-bootstrap/4.5.3/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('metisMenu/3.0.6/metisMenu.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('select2/4.0.9/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('iCheck/1.0.3/skins/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('font-awesome/5.15.1/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_cdn('node-waves/0.7.6/waves.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{admin_static('css/theme_'.config('admin.theme').'.css')}}" rel="stylesheet" type="text/css">
    {{--挂载引入区域--}}
    @yield('link.after')
    <script>$.ajaxSetup({headers:{'X-CSRF-TOKEN':window._token}});</script>
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
