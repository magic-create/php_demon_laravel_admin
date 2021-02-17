@section('frame')
    @if(config('admin.layout') == 'vertical')
        {{--左侧导航布局--}}
        <div class="wrapper">
            @if(!admin_tabs())
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
                        {{--Tab列表--}}
                        <ul class="dtabs nav" role="tablist">
                            @foreach($frames ?? [] as $index => $frame)
                                <li role="presentation" class="nav-item {{$index == count($frames)-1?'active':''}}" id="dtab_dtab_{{$frame['mid']}}" aria-url="{{$frame['path']}}">
                                    <a href="#dtab_{{$frame['mid']}}" aria-controls="dtab_{{$frame['mid']}}" role="tab" data-toggle="tab">{{$frame['title']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                {{--导航菜单--}}
                @yield('menu')
            @endif
            {{--正文部分--}}
            <div class="content-page">
                <div class="content {{config('admin.tabs') && !admin_tabs() ? 'dnav' : ''}}">
                    @foreach($frames ?? [] as $index => $frame)
                        <div id="dtab_{{$frame['mid']}}" class="dtabs-pane" role="tabpanel"></div>
                    @endforeach
                    <div class="container-fluid">
                        {{--页面标题和面包屑导航--}}
                        @yield('breadcrumb')
                        {{--正文内容--}}
                        @yield('page')
                        {{--Tab区域--}}
                        @if(config('admin.tabs') && !admin_tabs())
                            <script>$.admin.tabs.init({render:'.content-page > .content', symbol:'{{config('admin.tabs')}}', metismenu:'.metismenu', drop:240});</script>
                        @endif
                    </div>
                </div>
                {{--底部版权--}}
                @if(!config('admin.tabs') || admin_tabs())
                    <footer class="footer">@yield('footer')</footer>
                @endif
            </div>
        </div>
    @else
        {{--顶部导航布局--}}
        <header class="topbar">
            @if(!admin_tabs())
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
                            {{--Tab列表--}}
                            <ul class="dtabs nav" role="tablist">
                                @foreach($frames ?? [] as $index => $frame)
                                    <li role="presentation" class="nav-item {{$index == count($frames)-1?'active':''}}" id="dtab_dtab_{{$frame['mid']}}" aria-url="{{$frame['path']}}">
                                        <a href="#dtab_{{$frame['mid']}}" aria-controls="dtab_{{$frame['mid']}}" role="tab" data-toggle="tab">{{$frame['title']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if(!config('admin.tabs'))
                <div class="container-fluid">
                    {{--页面标题和面包屑导航--}}
                    @yield('breadcrumb')
                </div>
                {{--导航菜单--}}
                @yield('menu')
            @else
                @if(admin_tabs())
                    <div class="container-fluid">
                        {{--页面标题和面包屑导航--}}
                        @yield('breadcrumb')
                    </div>
                @else
                    {{--导航菜单--}}
                    <div class="dtabs-navs">
                        @yield('menu')
                    </div>
                @endif
            @endif
        </header>
        {{--正文部分--}}
        @if(config('admin.tabs'))
            <div class="wrapper {{config('admin.tabs') && !admin_tabs() ? 'dnav' : ''}}">
                @foreach($frames ?? [] as $index => $frame)
                    <div id="dtab_{{$frame['mid']}}" class="dtabs-pane" role="tabpanel"></div>
                @endforeach
                <div class="container-fluid">
                    {{--正文内容--}}
                    @yield('page')
                    {{--Tab区域--}}
                    @if(config('admin.tabs') && !admin_tabs())
                        <script>
                            $.admin.tabs.init({
                                render:'.wrapper', symbol:'{{config('admin.tabs')}}', metismenu:'.metismenu', offset:170, drop:280,
                                resize:function(e){
                                    e.settings.offset = e.width > 991 ? 165 : 55;
                                    e.settings.drop = e.width > 991 ? 280 : (e.width > 620 ? 320 : 240);
                                    return e.settings;
                                }
                            });
                        </script>
                    @endif
                </div>
            </div>
        @else
            <div class="wrapper">
                <div class="container-fluid">
                    {{--正文内容--}}
                    @yield('page')
                </div>
            </div>
        @endif
        {{--底部版权--}}
        @if(!config('admin.tabs') || admin_tabs())
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">@yield('footer')</div>
                    </div>
                </div>
            </footer>
        @endif
    @endif
@endsection
