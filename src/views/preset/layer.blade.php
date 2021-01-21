{{--引入框架--}}
@extends('admin::layout.base')
{{--传递引入信息--}}
@section('link')
    @yield('container.link')
@endsection
{{--传递样式信息--}}
@section('style')
    @yield('container.style')
@endsection
{{--传递页面内容--}}
@section('frame')
    @yield('container.content',__('No Content'))
@endsection
{{--传递脚本信息--}}
@section('script')
    @yield('container.script')
@endsection
