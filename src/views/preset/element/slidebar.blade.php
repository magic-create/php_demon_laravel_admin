@section('menu')
    @php
        $list = [
            [
                'icon' => 'tachometer-alt',
                'title' => 'Menu-1',
                'path' => 'javascript:',
            ],
            [
                'icon' => 'calendar-alt',
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
                    ['icon' => 'tachometer-alt', 'title' => 'Index', 'path' => admin_url('example')],
                    ['icon' => 'building', 'title' => 'Form', 'path' => admin_url('example/form')],
                    ['icon' => 'layer-group', 'title' => 'Layer', 'path' => admin_url('example/layer')],
                    ['icon' => 'table', 'title' => 'Table', 'path' => admin_url('example/table')],
                    ['icon' => 'magic', 'title' => 'Widget', 'path' => admin_url('example/widget')],
                    ['icon' => 'pen-fancy', 'title' => 'Editor', 'path' => admin_url('example/editor')],
                    ['icon' => 'marker', 'title' => 'Markdown', 'path' => admin_url('example/markdown')],
                    ['icon' => 'user-check', 'title' => 'Login', 'path' => admin_url('example/login')],
                ]
            ]
        ];
    @endphp
    {{--导航布局--}}
    <div class="sidebar-body {{config('admin.layout') == 'horizontal' ? 'container-fluid' : ''}}">
        <div class="slimscroll-menu">
            <nav class="sidebar-nav">
                {{--循环体--}}
                <ul class="metismenu" id="side-menu">
                    {{--主标题--}}
                    <li class="menu-title">{{config('admin.title')}}</li>
                    @foreach(array_to_object($list) as $key => $val)
                        <li class="{{($val->active ?? false) ? 'mm-active' : ''}}">
                            {{--标题区域--}}
                            <a href="{{$val->list ?? null ? 'javascript:' : $val->path}}" class="waves-effect {{$val->list ?? null ?  'has-arrow' : ''}}">
                                {{--图标--}}
                                <i class="fa fa-{{($val->icon ?? null) ? $val->icon : 'list'}}"></i>
                                {{--数字提醒--}}
                                @if($val->count ?? 0)
                                    <span class="badge badge-primary badge-pill float-right">{{$val->count}}</span>
                                @endif
                                {{--标题--}}
                                <span>{{$val->title}}</span>
                            </a>
                            {{--子菜单--}}
                            @if($val->list ?? null)
                                <ul class="mm-collapse {{($val->active ?? false) ? 'mm-show' : ''}}">
                                    @foreach($val->list as $v)
                                        <li class="{{$v->path == url()->current() ? 'mm-active' : ''}}">
                                            <a href="{{$v->path}}">
                                                {{--图标--}}
                                                <i class="fa fa-{{($v->icon ?? null) ? $v->icon : 'cube'}}"></i>
                                                {{--数字提醒--}}
                                                @if($v->count ?? 0)
                                                    <span class="badge badge-primary badge-pill float-right">{{$v->count}}</span>
                                                @endif
                                                {{--标题--}}
                                                <span>{{$v->title}}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
@endsection
