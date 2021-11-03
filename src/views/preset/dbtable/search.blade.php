{{--搜索表单区域--}}
<div id="{{$dbTableConfig->searchTemplate}}" class="dbTable_search d-none">
    @if($dbTableConfig->searchPanel)
        <form id="{{$dbTableConfig->searchForm}}" class="row">
            {{--循环搜索表单--}}
            @foreach($dbTableSearch->list ?? [] as $key => $val)
                @php
                    $val->where = $val->where ?? '';
                    $val->type = isset($val->type) ? $val->type : (in_array($val->where,['range','between']) ? 'num' : 'text');
                    $val->attrHtml = $val->attrHtml ?? '';
                    $val->title = $val->title ?? '';
                    $val->placeholder = $val->placeholder ?? (!in_array($val->type,['select']) ? $val->title : '');
                    $val->style = $val->style ?? '';
                @endphp
                <div class="form-group search-panel">
                    <label class="row">
                        <font class="col-3">{{$val->title}}</font>
                        <div class="col-9">
                            @switch($val->type)
                                {{--标准搜索框--}}
                                @case('text')
                                @if(in_array($val->where,['range','between']))
                                    <div class="row range">
                                        <div class="col-6">
                                            <input name="{{$val->name}}__start" placeholder="{{is_array($val->placeholder) ? $val->placeholder[0] : $val->placeholder}}" value="{{$val->value[0]??''}}" type="text" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="{{$val->name}}__end" placeholder="{{is_array($val->placeholder) ? $val->placeholder[1] : $val->placeholder}}" value="{{$val->value[1]??''}}" type="text" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                    </div>
                                @else
                                    <input name="{{$val->name}}" placeholder="{{$val->placeholder}}" value="{{$val->value??''}}" type="text" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                @endif
                                @break
                                {{--列表选择--}}
                                @case('select')
                                @php($options = admin_html('search',$val->option,'select'))
                                @if(in_array($val->where,['range','between']))
                                    <div class="row range">
                                        <div class="col-6">
                                            <select name="{{$val->name}}__start" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                                <option value="">{!!is_array($val->placeholder) ? $val->placeholder[0] : ($val->placeholder ?: '&nbsp;')!!}</option>
                                                @foreach($options as $k => $o)
                                                    <option @if(($val->value[0]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="{{$val->name}}__end" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                                <option value="">{!!is_array($val->placeholder) ? $val->placeholder[1] : ($val->placeholder ?: '&nbsp;')!!}</option>
                                                @foreach($options as $k => $o)
                                                    <option @if(($val->value[1]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <select name="{{$val->name}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        <option value="">{!!$val->placeholder ?: '&nbsp;'!!}</option>
                                        @foreach($options as $k => $o)
                                            <option @if(($val->value??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @break
                                {{--联动列表选择--}}
                                @case('linkage')
                                @php($val->option->level = $val->option->level ?? 2)
                                <div class="row linkage-{{$val->option->level}}">
                                    @for($i = 1; $i <= $val->option->level; $i++)
                                        <div class="col-{{12 / $val->option->level}}">
                                            <select name="{{$val->name}}__{{$i}}" data-level="{{$i}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}"></select>
                                        </div>
                                    @endfor
                                    <script id="search_{{$val->name}}">
                                        $(function(){
                                            var namespace = eval({{$dbTableConfig->namespace}});
                                            namespace = namespace || {};
                                            namespace.linkage = namespace.linkage || {};
                                            var selector = [];
                                            for(var i = 1; i <= {{$val->option->level}}; i++) selector.push('[name="{{$val->name}}__' + i + '"][data-level="' + i + '"]');
                                            namespace.linkage.{{$val->name}} = $.extend({selector}, JSON.parse('{!!json_encode($val->option)!!}'));
                                            $('#search_{{$val->name}}').remove();
                                        });
                                    </script>
                                </div>
                                @break
                                {{--数字和范围--}}
                                @case('num')
                                @if(in_array($val->where,['range','between']))
                                    <div class="row range">
                                        <div class="col-6">
                                            <input name="{{$val->name}}__start" placeholder="{{is_array($val->placeholder) ? $val->placeholder[0] : $val->placeholder}}" value="{{$val->value[0]??''}}" type="number"
                                                   class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="{{$val->name}}__end" placeholder="{{is_array($val->placeholder) ? $val->placeholder[1] : $val->placeholder}}" value="{{$val->value[1]??''}}" type="number" class="form-control"
                                                   {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                    </div>
                                @else
                                    <input name="{{$val->name}}" placeholder="{{$val->placeholder}}" value="{{$val->value??''}}" type="number" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                @endif
                                @break
                                {{--时间和范围--}}
                                @case('time')
                                @if(in_array($val->where,['range','between']))
                                    <div class="row range">
                                        <div class="col-6">
                                            <input name="{{$val->name}}__start" placeholder="{{is_array($val->placeholder) ? $val->placeholder[0] : $val->placeholder}}" value="{{$val->value[0]??''}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="{{$val->name}}__end" placeholder="{{is_array($val->placeholder) ? $val->placeholder[1] : $val->placeholder}}" value="{{$val->value[1]??''}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                    </div>
                                @else
                                    <input name="{{$val->name}}" placeholder="{{$val->placeholder}}" value="{{$val->value??''}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                @endif
                                @break
                                {{--自定义或其他--}}
                                @case('custom')
                                @default
                                <div data-search-placeholder="{{$val->placeholder}}" data-search-value="{{$val->value??''}}" data-search-name="{{$val->name}}" data-search-type="{{$val->type}}"></div>
                                @yield("search.{$val->type}.{$val->name}")
                            @endswitch
                        </div>
                    </label>
                </div>
            @endforeach
            <div class="form-group search-panel">
                <div class="row">
                    <div id="{{$dbTableConfig->searchTemplate}}_button" class="col-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary"></button>
                        <button type="reset" class="btn btn-secondary"></button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
