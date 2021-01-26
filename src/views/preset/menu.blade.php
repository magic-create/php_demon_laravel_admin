@section('menu')
    @php
        $list = [
            [
                'icon' => 'tachometer-alt',
                'title' => 'Menu-1',
                'path' => 'javascript:',
            ],
            [
                'icon' => 'magic',
                'title' => 'Menu-2',
                'list' => [
                    ['title' => 'Menu-2-1', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-2', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-3', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-4', 'path' => 'javascript:'],
                    ['title' => 'Menu-2-5', 'path' => 'javascript:']
                ]
            ],
            [
                'icon' => 'fingerprint',
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
            ],
            [
                'icon' => 'gifts',
                'title' => 'Example',
                'active' => request()->route()->uri == config('admin.path') . '/example/{act?}',
                'list' => [
                    ['title' => 'Index', 'path' => admin_url('example/index')],
                    ['title' => 'Form', 'path' => admin_url('example/form')],
                    ['title' => 'Layer', 'path' => admin_url('example/layer')],
                    ['title' => 'Table', 'path' => admin_url('example/table')],
                    ['title' => 'Widget', 'path' => admin_url('example/widget')],
                    ['title' => 'Editor', 'path' => admin_url('example/editor')],
                    ['title' => 'Markdown', 'path' => admin_url('example/markdown')],
                ]
            ]
        ];
    @endphp
    @if(config('admin.layout') == 'vertical')
        {{--左侧导航布局--}}
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">
                <div id="sidebar-menu">
                    {{--循环体--}}
                    <ul class="metismenu" id="side-menu">
                        {{--主标题--}}
                        <li class="menu-title">{{config('admin.title')}}</li>
                        @foreach(array_to_object($list) as $key => $val)
                            <li class="{{($val->active ?? false) ? 'active' : ''}}">
                                {{--标题区域--}}
                                <a href="{{$val->list ?? null ? 'javascript:' : $val->path}}" class="waves-effect {{($val->active ?? false) ? 'active' : ''}}">
                                    <i class="fa fa-{{($val->icon ?? null) ? $val->icon : 'list'}}"></i>
                                    {{--数字提醒--}}
                                    @if($val->count ?? 0)
                                        <span class="badge badge-primary badge-pill float-right">{{$val->count}}</span>
                                    @endif
                                    {{--标题和箭头--}}
                                    <span>{{$val->title}}@if(!($val->count ?? 0) && ($val->list ?? null))<span class="float-right menu-arrow"><i class="fa fa-angle-right"></i></span>@endif</span>
                                </a>
                                {{--子菜单--}}
                                @if($val->list ?? null)
                                    <ul class="submenu collapse">
                                        @foreach($val->list as $v)
                                            <li class="{{$v->path == url()->current() ? 'active' : ''}}"><a href="{{$v->path}}">{{$v->title}}</a></li>
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
                                    <i class="fa fa-{{($val->icon ?? null) ? $val->icon : 'list'}}"></i>
                                    <span>{{$val->title}}</span>
                                </a>
                                {{--子菜单--}}
                                @if($val->list ?? null)
                                    <ul class="submenu">
                                        @foreach($val->list as  $v)
                                            <li class="{{$v->path == url()->current() ? 'active' : ''}}"><a href="{{$v->path}}">{{$v->title}}</a></li>
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
