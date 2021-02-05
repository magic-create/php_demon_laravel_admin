@section('frame')
    @if(config('admin.layout') == 'vertical')
        {{--左侧导航布局--}}
        <div class="wrapper">
            <div class="topbar">
                {{--顶部左侧--}}
                <div class="topbar-left">
                    <a href="{{admin_url()}}" class="logo">
                        <i><img src="{{admin_static('images/logo/sm.png')}}" title="{{config('admin.title')}}" height="32" style="vertical-align:sub"></i>
                        <span><img src="{{admin_static('images/logo/'.config('admin.layout').'-'.config('admin.theme').'.png')}}" title="{{config('admin.title')}}" height="36"></span>
                    </a>
                </div>
                {{--自定义部分--}}
                <nav class="topbar-custom">
                    <ul class="topbar-right d-flex list-inline float-right mb-0">@yield('topbar')</ul>
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="enlarged-toggle open-left waves-effect"><i class="fa fa-bars"></i></button>
                        </li>
                    </ul>
                </nav>
            </div>
            {{--导航菜单--}}
            @yield('menu')
            {{--正文部分--}}
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        {{--页面标题和面包屑导航--}}
                        @yield('breadcrumb')
                        {{--正文内容--}}
                        @yield('page')
                    </div>
                </div>
                {{--底部版权--}}
                <footer class="footer">@yield('footer')</footer>
            </div>
        </div>
    @else
        {{--顶部导航布局--}}
        <header class="topbar">
            <div class="topbar-main">
                <div class="container-fluid clearfix">
                    {{--顶部左侧--}}
                    <div class="logo">
                        <a href="{{admin_url()}}" class="logo">
                            <img src="{{admin_static('images/logo/sm.png')}}" title="{{config('admin.title')}}" class="logo-small" height="30">
                            <img src="{{admin_static('images/logo/'.config('admin.layout').'-'.config('admin.theme').'.png')}}" title="{{config('admin.title')}}" class="logo-large" height="30">
                        </a>
                    </div>
                    {{--自定义部分--}}
                    <div class="menu-extras topbar-custom clearfix">
                        <ul class="float-right list-unstyled mb-0 clearfix">@yield('topbar')</ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                {{--页面标题和面包屑导航--}}
                @yield('breadcrumb')
            </div>
            {{--导航菜单--}}
            @yield('menu')
        </header>
        {{--正文部分--}}
        <div class="wrapper">
            <div class="container-fluid">
                {{--正文内容--}}
                @yield('page')
            </div>
        </div>
        {{--底部版权--}}
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">@yield('footer')</div>
                </div>
            </div>
        </footer>
    @endif
@endsection
