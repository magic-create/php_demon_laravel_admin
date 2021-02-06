@section('topbar')
    {{--搜索区域--}}
    <li class="dropdown notification-list d-none d-sm-block">
        {{--搜索表单--}}
        <form role="search" class="app-search">
            <div class="form-group mb-0">
                <input type="text" class="form-control" placeholder="Search..">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </li>
    {{--操作区域--}}
    <li class="dropdown notification-list">
        {{--显示按钮和图标--}}
        <a class="nav-link dropdown-toggle arrow-none waves-effect" title="Action" data-toggle="dropdown" href="javascript:" role="button" aria-haspopup="false" aria-expanded="false"><i class="far fa-trash-alt noti-icon"></i></a>
        {{--菜单部分--}}
        <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:">Action 1</a>
            <a class="dropdown-item" href="javascript:">Action 2</a>
            <a class="dropdown-item" href="javascript:">Action 3</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:">Action 4</a>
            <a class="dropdown-item" href="javascript:">Action 5</a>
        </div>
    </li>
    {{--切换语言--}}
    <li class="dropdown notification-list">
        <a class="nav-link dropdown-toggle arrow-none waves-effect" action="locale" title="Locale" href="javascript:" role="button" aria-haspopup="false" aria-expanded="false"><i class="fa fa-language noti-icon"></i></a>
    </li>
    {{--通知区域--}}
    <li class="dropdown notification-list">
        @php($_notifications = rand(0,10))
        {{--显示图标和数字--}}
        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="javascript:" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="far fa-bell noti-icon"></i>
            @if($_notifications)
                <span class="badge badge-pill badge-danger noti-icon-badge">{{$_notifications}}</span>
            @endif
        </a>
        {{--菜单部分--}}
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
            @if($_notifications)
                {{--标题--}}
                <h6 class="dropdown-item-text">Notifications ({{$_notifications}})</h6>
                {{--内容列表--}}
                <div class="slimscroll notification-item-list">
                    @for ($i = 0; $i < min($_notifications,5); $i++)
                        <a href="javascript:" class="dropdown-item notify-item">
                            <div class="notify-icon bg-success"><i class="fas fa-exclamation"></i></div>
                            <p class="notify-details">Notifications {{$i+1}} Title<span class="text-muted">Notifications {{$i+1}} Content</span></p>
                        </a>
                    @endfor
                </div>
            @endif
            {{--查看全部--}}
            <a href="javascript:" class="dropdown-item text-center text-primary">View all <i class="fi-arrow-right"></i></a>
        </div>
    </li>
    {{--用户区域--}}
    <li class="dropdown notification-list">
        <div class="dropdown notification-list nav-pro-img">
            {{--用户头像--}}
            <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="javascript:" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{admin_static('images/avatar/1.jpg')}}" alt="user" class="rounded-circle">
            </a>
            {{--菜单部分--}}
            <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                <a class="dropdown-item" href="javascript:"><i class="fa fa-user-circle mr-2"></i> Profile</a>
                <a class="dropdown-item" href="javascript:"><i class="fa fa-wallet mr-2"></i> My Wallet</a>
                <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="fa fa-cog mr-2"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:"><i class="fa fa-power-off text-danger"></i> Logout</a>
            </div>
        </div>
    </li>
    {{--移动版菜单按钮--}}
    <li class="menu-item">
        <a class="navbar-toggle nav-link" id="mobileToggle">
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
        });
    </script>
@endsection
