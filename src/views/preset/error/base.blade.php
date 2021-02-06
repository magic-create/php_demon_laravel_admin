{{--引入框架--}}
@extends('admin::layout.base')
{{--传递引入信息--}}
@section('link.before')
    @yield('container.link.before')
@endsection
{{--传递引入信息--}}
@section('link.after')
    @yield('container.link.after')
@endsection
{{--传递样式信息--}}
@section('style')
    @yield('container.style')
@endsection
{{--加载预设尾部--}}
@include(config('admin.element.footer'))
{{--传递页面内容--}}
@section('frame')
    <div class="wrapper-page">
        <div class="card">
            <div class="error-content text-center">
                <h1>@yield('code')!</h1>
                <h4>@yield('message')</h4><br>
                <a class="btn btn-info mb-4 waves-effect waves-float" href="javascript:" onclick="goBack()"><i class="fa fa-backspace mr-2"></i>{{admin_error('back')}}</a>
            </div>
        </div>
        <footer class="mt-4 text-center">
            @yield('footer')
        </footer>
    </div>
@endsection
{{--传递脚本信息--}}
@section('script')
    <script>
        goBack = function(){
            var ua = navigator.userAgent;
            if((ua.indexOf('MSIE') >= 0) && (ua.indexOf('Opera') < 0)){
                if(history.length > 0) history.go(-1);
                else location.href = '{{admin_url()}}';
            }else{
                if(ua.indexOf('Firefox') >= 0 || ua.indexOf('Opera') >= 0 || ua.indexOf('Safari') >= 0 || ua.indexOf('Chrome') >= 0 || ua.indexOf('WebKit') >= 0){
                    if(history.length > 1)
                        history.go(-1);
                    else location.href = '{{admin_url()}}';
                }else history.go(-1);
            }
        };
    </script>
    @yield('container.script')
@endsection
