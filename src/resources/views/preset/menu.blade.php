@section('menu')
    @php
        $list = [
            [
                'icon' => 'view-dashboard',
                'title' => 'Menu-1',
                'path' => 'javascript:',
            ],
            [
                'icon' => 'memory',
                'title' => 'Menu-2',
                'active' => true,
                'list' => [
                    ['title' => 'Menu-2-1', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-2', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-3', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-4', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-5', 'path' => 'javascript:', 'active' => true]
                ]
            ],
            [
                'icon' => 'medium',
                'title' => 'Menu-3',
                'path' => 'javascript:',
                'count' => rand(1, 10),
            ],
            [
                'icon' => '',
                'title' => 'Menu-4',
                'count' => rand(0, 1) ? rand(0, 10) : 0,
                'list' => [
                    ['title' => 'Menu-4-1', 'path' => 'javascript:'],
                    ['title' => 'Menu-4-2', 'path' => 'javascript:'],
                    ['title' => 'Menu-4-3', 'path' => 'javascript:'],
                    ['title' => 'Menu-4-4', 'path' => 'javascript:'],
                    ['title' => 'Menu-4-5', 'path' => 'javascript:'],
                ]
            ]
        ];
    @endphp
    @if(config('admin.web.style.layout') == 'vertical')
        {{--左侧导航布局--}}
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">
                <div id="sidebar-menu">
                    {{--循环体--}}
                    <ul class="metismenu" id="side-menu">
                        {{--主标题--}}
                        <li class="menu-title">{{config('admin.web.title')}}</li>
                        @foreach(array_to_object($list) as $key => $val)
                            <li class="{{($val->active ?? false) ? 'active' : ''}}">
                                {{--标题区域--}}
                                <a href="{{$val->list ?? null ? 'javascript:' : $val->path}}" class="waves-effect {{($val->active ?? false) ? 'active' : ''}}">
                                    <i class="mdi mdi-{{($val->icon ?? null) ? $val->icon : 'format-list-bulleted-type'}}"></i>
                                    {{--数字提醒--}}
                                    @if($val->count ?? 0)
                                        <span class="badge badge-primary badge-pill float-right">{{$val->count}}</span>
                                    @endif
                                    {{--标题和箭头--}}
                                    <span>{{$val->title}}@if(!($val->count ?? 0) && ($val->list ?? null))<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>@endif</span>
                                </a>
                                {{--子菜单--}}
                                @if($val->list ?? null)
                                    <ul class="submenu">
                                        @foreach($val->list as $v)
                                            <li class="{{($v->active ?? false) ? 'active' : ''}}"><a href="{{$v->path}}">{{$v->title}}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    @else
        {{--顶部导航布局--}}
        <div class="navbar-custom navbar-transparent">
            <div class="container-fluid">
                <div id="navigation">
                    {{--循环体--}}
                    <ul class="navigation-menu">
                        @foreach(array_to_object($list) as $key => $val)
                            <li class="has-submenu {{($val->active ?? false) ? 'active' : ''}}">
                                {{--标题区域--}}
                                <a href="{{$val->list ?? null ? 'javascript:' : $val->path}}">
                                    <i class="mdi mdi-{{($val->icon ?? null) ? $val->icon : 'format-list-bulleted-type'}}"></i>
                                    <span>{{$val->title}}</span>
                                </a>
                                {{--子菜单--}}
                                @if($val->list ?? null)
                                    <ul class="submenu">
                                        @foreach($val->list as  $v)
                                            <li class="{{($v->active ?? false) ? 'active' : ''}}"><a href="{{$v->path}}">{{$v->title}}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endsection
