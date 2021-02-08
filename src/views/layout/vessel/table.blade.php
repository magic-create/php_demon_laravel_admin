{{--引入框架--}}
@extends('admin::layout.base')
{{--传递引入信息--}}
@section('link.before')
    <script src="{{admin_cdn('bootstrap-table/1.18.1/bootstrap-table.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-table/1.18.1/extensions/toolbar/bootstrap-table-toolbar.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-table/1.18.1/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-table/1.18.1/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js')}}"></script>
    <link href="{{admin_cdn('bootstrap-table/1.18.1/bootstrap-table.min.css')}}" rel="stylesheet" type="text/css">
    @yield('table.link.before')
@endsection
{{--传递引入信息--}}
@section('link.after')
    @yield('table.link.after')
@endsection
{{--传递样式信息--}}
@section('style')
    @yield('table.style')
@endsection

@if(!($layer ?? false))
    {{--加载预设头部--}}
    @include(config('admin.element.topbar'))
    {{--加载主菜单--}}
    @include(config('admin.element.slidebar'))
    {{--加载预设尾部--}}
    @include(config('admin.element.footer'))
    {{--加载预设标题和面包屑导航--}}
    @include(config('admin.element.breadcrumb'))
@endif

{{--传递页面内容--}}
@section(!($layer ?? false) ? 'page' : 'frame')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    @yield('table.content')
                </div>
            </div>
        </div>
    </div>
@endsection
{{--传递脚本信息--}}
@section('script')
    @yield('table.script')
@endsection

@if(!($layer ?? false))
    {{--调用默认布局--}}
    @include('admin::layout.frame')
@endif
