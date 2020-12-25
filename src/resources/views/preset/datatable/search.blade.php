{{--搜索表单区域--}}
<script id="{{$dataTableSearch->template}}" type="text/template">
    @if($dataTableSearch->status)
    {{--循环搜索表单--}}
    @foreach($dataTableSearch->list ?? [] as $key => $val)
    @php
        $val->where = $val->where ?? '';
        $val->type = isset($val->type) ? $val->type :($val->where == 'range' ? 'num' : 'input');
        $val->attrHtml = $val->attrHtml ?? '';
        $val->title = $val->title ?? '';
        $val->style = $val->style ?? '';
    @endphp
    @switch($val->type)
    {{--标准搜索框--}}
    @case('input')
    <label>
        <font>{{$val->title}}</font>
        <input name="{{$val->name}}" value="{{$val->value??''}}" type="text" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
    </label>
    @break
    {{--列表选择--}}
    @case('select')
    @php($options = adminHtml('search',$val->rule,'select'))
    <label><font>{{$val->title}}</font>
        @if(($val->where) == 'range')
            <select name="{{$val->name}}__start" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
                <option></option>
                @foreach($options as $k => $o)
                    <option @if(($val->value[0]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                @endforeach
            </select>
            <i style="margin-right:0.1rem;">~</i>
            <select name="{{$val->name}}__end" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
                <option></option>
                @foreach($options as $k => $o)
                    <option @if(($val->value[1]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                @endforeach
            </select>
        @else
            <select name="{{$val->name}}" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
                <option></option>
                @foreach($options as $k => $o)
                    <option @if(($val->value??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                @endforeach
            </select>
        @endif
    </label>
    @break
    {{--数字和范围--}}
    @case('num')
    <label><font>{{$val->title}}</font>
        @if(($val->where) == 'range')
            <input name="{{$val->name}}__start" value="{{$val->value[0]??''}}" type="number" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
            <i style="margin-right:-0.3rem;">~</i>
            <input name="{{$val->name}}__end" value="{{$val->value[1]??''}}" type="number" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
        @else
            <input name="{{$val->name}}" value="{{$val->value??''}}" type="number" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
        @endif
    </label>
    @break
    {{--时间和范围--}}
    @case('time')
    <label><font>{{$val->title}}</font>
        @if(($val->where) == 'range')
            <input name="{{$val->name}}__start" value="{{$val->value[0]??''}}" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
            <i style="margin-right:-0.3rem;">~</i>
            <input name="{{$val->name}}__end" value="{{$val->value[1]??''}}" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
        @else
            <input name="{{$val->name}}" value="{{$val->value??''}}" class="form-control input-sm" {!!$val->attrHtml!!} style="width:90px;{{$val->style}}">
        @endif
    </label>
    @break
    {{--自定义或其他--}}
    @case('custom')
    @default
    <label><font>{{$val->title}}</font><i data-search-value="{{$val->value??''}}" data-search-name="{{$val->name}}" data-search-type="{{$val->type}}"></i>@yield("search.{$val->type}.{$val->name}")</label>
    @endswitch
    @endforeach
    @endif
</script>
