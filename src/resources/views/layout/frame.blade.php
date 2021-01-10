@section('frame')
    @if(config('admin.web.style.layout') == 'vertical')
        {{--左侧导航布局--}}
        <div id="wrapper">
            <div class="topbar">
                {{--顶部左侧--}}
                <div class="topbar-left">
                    <a href="{{adminUrl()}}" class="logo">
                        <i><img src="/static/admin/images/logo-sm.png" alt="{{config('admin.web.title')}}" title="{{config('admin.web.title')}}" height="22"></i>
                        <span><img src="/static/admin/images/logo-{{config('admin.web.style.theme')}}.png" alt="{{config('admin.web.title')}}" title="{{config('admin.web.title')}}" height="18"></span>
                    </a>
                </div>
                {{--自定义部分--}}
                <nav class="navbar-custom">
                    <ul class="navbar-right d-flex list-inline float-right mb-0">@yield('header')</ul>
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-effect">
                                <i class="fa fa-bars"></i>
                            </button>
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
        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid clearfix">
                    {{--顶部左侧--}}
                    <div class="logo">
                        <a href="{{adminUrl()}}" class="logo">
                            <img src="/static/admin/images/logo-sm.png" alt="{{config('admin.web.title')}}" title="{{config('admin.web.title')}}" class="logo-small">
                            <img src="/static/admin/images/logo-{{config('admin.web.style.theme')}}.png" alt="{{config('admin.web.title')}}" title="{{config('admin.web.title')}}" class="logo-large">
                        </a>
                    </div>
                    {{--自定义部分--}}
                    <div class="menu-extras topbar-custom clearfix">
                        <ul class="float-right list-unstyled mb-0 clearfix">@yield('header')</ul>
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
