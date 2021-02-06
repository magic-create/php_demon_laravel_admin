@section('topbar')
    {{--通知区域--}}
    @php($notifications = app('admin')->getNotification())
    <li class="dropdown notification-list">
        {{--显示图标和数字--}}
        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="{{$notifications ? 'javascript:' : admin_url('auth/setting')}}" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="far fa-bell noti-icon"></i>
            @if($notifications)
                <span class="badge badge-pill badge-danger noti-icon-badge">{{count($notifications)}}</span>
            @endif
        </a>
        {{--菜单部分--}}
        @if($notifications)
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                {{--标题--}}
                <h6 class="dropdown-item-text">{{app('admin')->__('base.auth.notifications')}} ({{count($notifications)}})</h6>
                {{--内容列表--}}
                <div class="slimscroll notification-item-list">
                    @foreach ($notifications as $item)
                        <a href="{{$item['path']}}" class="dropdown-item notify-item">
                            <div class="notify-icon bg-{{$item['theme']}}"><i class="{{$item['icon']}}"></i></div>
                            <p class="notify-details">{{$item['title']}}<span class="text-muted">{{$item['content']}}</span></p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </li>
    {{--用户区域--}}
    <li class="dropdown notification-list">
        <div class="dropdown notification-list nav-pro-img">
            {{--用户头像--}}
            <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="javascript:" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{app('admin')->getUserAvatar($user->avatar??null, $user->nickname??null)}}" class="rounded-circle">
            </a>
            {{--菜单部分--}}
            <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                <a class="dropdown-item" href="javascript:" action="clear"><i class="far fa-trash-alt"></i> {{app('admin')->__('base.auth.cache')}}</a>
                <a class="dropdown-item" href="{{admin_url('auth/setting')}}"><i class="fa fa-cog"></i> {{app('admin')->__('base.auth.setting')}}</a>
                <a class="dropdown-item" href="javascript:" action="locale"><i class="fa fa-language"></i> {{app('admin')->__('base.auth.locale')}}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:" action="logout"><i class="fa fa-power-off text-danger"></i> {{app('admin')->__('base.auth.logout')}}</a>
            </div>
        </div>
    </li>
    {{--移动版菜单按钮--}}
    <li class="menu-item">
        <a class="topbar-toggle nav-link">
            <div class="lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>
    </li>
    <script>
        $(function(){
            $('[action="locale"]').on('click', function(){
                $.admin.layer.radio({
                    current:'{{app()->getLocale()}}',
                    list:JSON.parse('{!!json_encode(config('admin.locales'))!!}')
                }, {title:'{{app('admin')->__('base.auth.locale')}}'}, function(index, layero){
                    $.post('{{admin_url('auth/locale')}}', {locale:layero.checked}, function(data){
                        $.admin.api.success(data, function(){
                            $.admin.layer.close(index);
                            $.admin.layer.alert(data.message, {icon:1, time:3000, end:function(){location.href = location.href;}});
                        });
                    }).fail($.admin.api.fail);
                });
            });
            $('[action="clear"]').on('click', function(){$.post('{{admin_url('auth/clear')}}', function(data){$.admin.api.success(data, function(data){$.admin.alert.success(data.message);});}).fail($.admin.api.fail);});
            $('[action="logout"]').on('click', function(){
                $.admin.layer.confirm('{{app('admin')->__('base.auth.logout_confirm')}}', function(index){
                    $.post('{{admin_url('auth/logout')}}', function(data){
                        $.admin.api.success(data, function(){
                            $.admin.layer.close(index);
                            $.admin.layer.alert(data.message, {icon:1, time:3000, end:function(){location.href = location.href;}});
                        });
                    }).fail($.admin.api.fail);
                });
            });
        });
    </script>
@endsection
