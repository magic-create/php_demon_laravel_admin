@extends('admin::layout.base')
@include('admin::layout.element.footer')
@section('link.before')
    @if(config('admin.tabs'))
        <script>
            if(window.self != window.top) window.top.location.href = window.self.location.href;
        </script>
    @endif
@endsection
@section('frame')
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center m-0"><a href="javascript:" class="logo logo-admin"><img src="{{admin_static('images/logo/horizontal-'.config('admin.theme'))}}.png" alt="{{config('admin.title')}}" title="{{config('admin.title')}}" height="30"></a></h3>
                <div class="p-3">
                    <h4 class="font-18 mb-1 text-center">{{config('admin.title')}}</h4>
                    <p class="text-center">{{app('admin')->__('base.auth.subtitle')}}</p>
                    <form id="validate" class="form-horizontal mt-2">
                        <div class="form-group">
                            <label>{{app('admin')->__('base.auth.account')}}</label>
                            <input type="text" name="account" class="form-control" placeholder="{{app('admin')->__('base.auth.enter_account')}}">
                        </div>
                        <div class="form-group">
                            <label>{{app('admin')->__('base.auth.password')}}</label>
                            <input type="password" name="password" class="form-control" placeholder="{{app('admin')->__('base.auth.enter_password')}}">
                        </div>
                        <div class="form-group">
                            <label>{{app('admin')->__('base.auth.captcha')}}</label>
                            <div class="input-group">
                                <input type="text" name="captcha" class="form-control" placeholder="{{app('admin')->__('base.auth.enter_captcha')}}">
                                <div class="input-group-append">
                                    <img class="input-group-text image-captcha" src="{{admin_url('extend/image/captcha')}}" onclick="this.src='{{admin_url('extend/image/captcha')}}?_='+Math.random()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="check-group"><label><input name="remember" class="checkbox" type="checkbox">{{app('admin')->__('base.auth.remember')}}</label></div>
                        </div>
                        <button class="mt-2 btn btn-lg btn-block btn-primary waves-effect waves-light" type="submit">{{app('admin')->__('base.auth.login')}}</button>
                    </form>
                </div>
            </div>
        </div>
        <footer class="mt-4 text-center">@yield('footer')</footer>
    </div>
@endsection
@section('script')
    <script>
        var backgroundImage = '{{$backgroundImage ?? ''}}';
        if(backgroundImage) $('body').addClass('background').css({background:'url(' + backgroundImage + ')'});
        $.admin.form('#validate', {
            render:true,
            list:{
                account:{required:{message:'{{app('admin')->__('base.auth.error_account')}}'}},
                password:{required:{message:'{{app('admin')->__('base.auth.error_password')}}'}},
                captcha:{function:{rule:function(e){return $(e).val().length == '{{config('admin.captcha.length')}}'; }, message:'{{app('admin')->__('base.auth.error_captcha')}}'}}
            },
            callback:{
                build:function(form){
                    var account = window.localStorage && localStorage.getItem('admin.account');
                    if(account){
                        $(form.selector).find('[name="account"]').val(account);
                        $(form.selector).find('[name="remember"]').attr('checked', true);
                    }
                },
                success:function(e){
                    var value = e.value();
                    value.password = $.md5(value.password);
                    $.admin.layer.load(1);
                    var fail = function(e){
                        $('.image-captcha').click();
                        $.admin.layer.alert(e.message, {icon:2});
                    };
                    $.ajax({
                        type:'POST',
                        url:'{{admin_url('auth/login')}}',
                        data:value,
                        success:function(data){
                            $.admin.api.success(data, function(data){
                                window.localStorage ? (value.remember ? localStorage.setItem('admin.account', value.account) : localStorage.removeItem('admin.account')) : null;
                                $.admin.layer.alert(data.message, {icon:1, time:3000, end:function(){location.href = data.data.url;}});
                            }, fail);
                        },
                        error:function(xhr){$.admin.api.fail(xhr, fail);},
                        complete:function(){ $.admin.layer.closeAll('loading'); }
                    });
                }
            }
        });
    </script>
@endsection
