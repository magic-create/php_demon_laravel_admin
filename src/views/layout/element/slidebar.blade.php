@section('menu')
    {{--导航布局--}}
    <div class="sidebar-body {{config('admin.layout') == 'horizontal' ? 'container-fluid' : ''}}">
        <div class="slimscroll-menu">
            <nav class="sidebar-nav">
                {{--循环体--}}
                <ul class="metismenu" id="side-menu">
                    {{--主标题--}}
                    <li class="menu-title">{{config('admin.title')}}</li>
                    {{--输出列表--}}
                    {!!app('admin')->getUserMenuHtml()!!}
                </ul>
            </nav>
        </div>
    </div>
@endsection
