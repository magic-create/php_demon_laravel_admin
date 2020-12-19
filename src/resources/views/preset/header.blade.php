@section('header')
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
    {{--通知区域--}}
    <li class="dropdown notification-list">
        @php $_notifications = rand(0,10) @endphp
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
            <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{$view->asset}}/images/users/1.jpg" alt="user" class="rounded-circle">
            </a>
            {{--菜单部分--}}
            <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                <a class="dropdown-item" href="javascript:"><i class="mdi mdi-account-circle m-r-5"></i> Profile</a>
                <a class="dropdown-item" href="javascript:"><i class="mdi mdi-wallet m-r-5"></i> My Wallet</a>
                <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings m-r-5"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:"><i class="mdi mdi-power text-danger"></i> Logout</a>
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
@endsection
