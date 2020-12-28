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
{{--加载预设头部--}}
@include('admin::preset.header')
{{--加载主菜单--}}
@include('admin::preset.menu')
{{--加载预设尾部--}}
@include('admin::preset.footer')
{{--加载预设标题和面包屑导航--}}
@include('admin::preset.breadcrumb')
{{--传递页面内容--}}
@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @yield('container.content',__('admin.preset.container.none'))
                </div>
            </div>
        </div>
    </div>
@endsection
{{--传递脚本信息--}}
@section('script')
    @yield('container.script')
@endsection
{{--调用默认布局--}}
@include('admin::layout.frame')
