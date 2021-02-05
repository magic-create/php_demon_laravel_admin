@extends('admin::layout.vessel.blank')
@section('container.content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Progress Bars</h4>
                    <p class="text-muted mb-4">Bootstrap 进度条</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <h6>默认效果</h6>
                            <div class="progress">
                                <div class="progress-bar" style="width:{{rand(1e1,1e2)}}%"></div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <h6>显示数值</h6>
                            <div class="progress">
                                @php($rand=rand(1e1,1e2))
                                <div class="progress-bar progress-bar-info" style="width:{{$rand}}%">{{$rand}}%</div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <h6>调整高度</h6>
                            <div class="progress" style="height:1.5rem">
                                <div class="progress-bar progress-bar-success" style="width:{{rand(1e1,1e2)}}%"></div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <h6>斜纹覆盖</h6>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-warning" style="width:{{rand(1e1,1e2)}}%"></div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <h6>斜纹动画</h6>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-danger" style="width:{{rand(1e1,1e2)}}%"></div>
                            </div>
                        </li>
                        <li>
                            <h6>多重叠加</h6>
                            <div class="progress">
                                <div class="progress-bar progress-bar-light" style="width:{{rand(1e1,3e1)}}%"></div>
                                <div class="progress-bar progress-bar-secondary" style="width:{{rand(1e1,3e1)}}%"></div>
                                <div class="progress-bar progress-bar-dark" style="width:{{rand(1e1,3e1)}}%"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Popovers</h4>
                    <p class="text-muted mb-4">Bootstrap 弹出框</p>
                    <div class="button-items">
                        <button type="button" class="btn btn-primary waves-effect" data-toggle="popover" data-content="这里是弹出内容，再次点击关闭">点击弹出</button>
                        <button type="button" class="btn btn-secondary waves-effect" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="这里是弹出内容，移出则关闭">触摸弹出</button>
                        <button type="button" class="btn btn-info waves-effect" data-toggle="popover" data-placement="bottom" data-trigger="focus" data-content="这里是弹出内容，点击它处则关闭">焦点弹出</button>
                        <button type="button" class="btn btn-success waves-effect" data-toggle="popover" data-placement="left" data-title="标题" data-content="这里是弹出内容，再次点击关闭">标题内容</button>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Tooltips</h4>
                    <p class="text-muted mb-4">Bootstrap 提示框</p>
                    <div class="button-items">
                        <button type="button" class="btn btn-warning waves-effect" data-toggle="tooltip" title="这里是提示内容">默认位置</button>
                        <button type="button" class="btn btn-danger waves-effect" data-toggle="tooltip" data-placement="bottom" title="这里是提示内容">底部位置</button>
                        <button type="button" class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="left" title="这里是提示内容">左侧位置</button>
                        <button type="button" class="btn btn-dark waves-effect" data-toggle="tooltip" data-placement="right" data-html="true" title="<em>Tooltip</em> <u>with</u> <b>HTML</b>">右侧HTML</button>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Dropdowns</h4>
                    <p class="text-muted mb-4">Bootstrap 下拉菜单</p>
                    <ul class="list-unstyled button-items">
                        <li class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" id="dropdownMenu-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">默认效果</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu-1">
                                <a class="dropdown-item" href="javascript:">Action-1</a>
                                <a class="dropdown-item" href="javascript:">Action-2</a>
                                <a class="dropdown-item" href="javascript:">Action-3</a>
                            </div>
                        </li>
                        <li class="btn-group dropup">
                            <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">选中和禁用</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu-2">
                                <a class="dropdown-item active" href="javascript:">Action-1</a>
                                <a class="dropdown-item" href="javascript:">Action-2</a>
                                <a class="dropdown-item disabled" href="javascript:">Action-3</a>
                            </div>
                        </li>
                        <li class="btn-group">
                            <div class="btn-group dropleft">
                                <button class="btn btn-info dropdown-toggle dropdown-toggle-split" id="dropdownMenu-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu-3">
                                    <div class="dropdown-header">Dropdown header 1</div>
                                    <a class="dropdown-item" href="javascript:">Action-1</a>
                                    <a class="dropdown-item" href="javascript:">Action-2</a>
                                    <a class="dropdown-item" href="javascript:">Action-3</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:">Action-4</a>
                                </div>
                            </div>
                            <button class="btn btn-info">标题和分割线</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Collapse</h4>
                    <p class="text-muted mb-4">Bootstrap 折叠</p>
                    <div id="accordion">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="card">
                                <div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse-{{$i}}">选项{{$i+1}}</a></div>
                                <div id="collapse-{{$i}}" class="collapse {{$i == 2 ? 'show' : ''}}" data-parent="#accordion">
                                    <div class="card-body">#{{$i+1}} 内容：测试同时只能展开1个</div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Badge</h4>
                    <p class="text-muted mb-4">Bootstrap 标记</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <h1>测试标题 - H1 <span class="badge badge-light">{{rand(1,1e2)}}</span></h1>
                            <h2>测试标题 - H2 <span class="badge badge-primary">{{rand(1,1e2)}}</span></h2>
                            <h3>测试标题 - H3 <span class="badge badge-info">{{rand(1,1e2)}}</span></h3>
                            <h4>测试标题 - H4 <span class="badge badge-success">{{rand(1,1e2)}}</span></h4>
                            <h5>测试标题 - H5 <span class="badge badge-dark">{{rand(1,1e2)}}</span></h5>
                            <h6>测试标题 - H6 <span class="badge badge-warning">{{rand(1,1e2)}}</span></h6>
                        </li>
                        <li>
                            <span class="badge badge-pill badge-light">Light</span>
                            <span class="badge badge-pill badge-primary">Primary</span>
                            <span class="badge badge-pill badge-secondary">Secondary</span>
                            <span class="badge badge-pill badge-info">Info</span>
                            <span class="badge badge-pill badge-success">Success</span>
                            <span class="badge badge-pill badge-warning">Warning</span>
                            <span class="badge badge-pill badge-danger">Danger</span>
                            <span class="badge badge-pill badge-dark">Dark</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Carousel</h4>
                    <p class="text-muted mb-4">Bootstrap 轮播</p>
                    <div id="carousel" class="carousel slide" data-ride="carousel">
                        <ul class="carousel-indicators">
                            @for ($i = 1; $i <= 5; $i++)
                                <li data-target="#carousel" data-slide-to="{{$i-1}}" class="{{$i == 1 ? 'active' : ''}}"></li>
                            @endfor
                        </ul>
                        <div class="carousel-inner">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="carousel-item {{$i == 1 ? 'active' : ''}}"><img src="/static/admin/images/avatar/{{$i}}.jpg" width="100%" height="100%"></div>
                            @endfor
                        </div>
                        <a class="carousel-control-prev" href="#carousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                        <a class="carousel-control-next" href="#carousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mt-0 header-title">List</h4>
                    <p class="text-muted mb-4">Bootstrap 列表组</p>
                    <ul class="list-group">
                        <li class="list-group-item">Default item</li>
                        <li class="list-group-item list-group-item-action">Action item</li>
                        <li class="list-group-item active">Active item</li>
                        <li class="list-group-item disabled">Disabled item</li>
                        <li class="list-group-item list-group-item-primary">Primary item</li>
                        <li class="list-group-item list-group-item-secondary">Secondary item</li>
                        <li class="list-group-item list-group-item-info">Info item</li>
                        <li class="list-group-item list-group-item-success">Success item</li>
                        <li class="list-group-item list-group-item-warning">Warning item</li>
                        <li class="list-group-item list-group-item-danger">Danger item</li>
                        <li class="list-group-item list-group-item-dark">Dark item</li>
                        <li class="list-group-item list-group-item-light">Light item</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    <script>
    </script>
@endsection
