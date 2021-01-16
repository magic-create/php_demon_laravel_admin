{{--按钮区域--}}
<div id="{{ltrim($dbTableConfig->toolbar,'#.')}}" class="{{ltrim($dbTableConfig->toolbar,'#.')}}">
    @foreach($dbTableButton as $key => $val)
        {!! $val->html !!}
    @endforeach
    {{--扩展区域--}}
    @yield("toolbar.button")
</div>
