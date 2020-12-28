{{--搜索表单区域--}}
<div id="{{$dbTableConfig->searchTemplate}}" class="dbTable_search">
    @if($dbTableConfig->searchPanel)
        <form id="{{$dbTableConfig->searchForm}}" class="row">
            {{--循环搜索表单--}}
            @foreach($dbTableSearch->list ?? [] as $key => $val)
                @php
                    $val->where = $val->where ?? '';
                    $val->type = isset($val->type) ? $val->type :($val->where == 'range' ? 'num' : 'input');
                    $val->attrHtml = $val->attrHtml ?? '';
                    $val->title = $val->title ?? '';
                    $val->placeholder = $val->placeholder ?? $val->title;
                    $val->style = $val->style ?? '';
                @endphp
                <div class="form-group search-panel">
                    <label class="row">
                        <font class="col-3">{{$val->title}}</font>
                        <div class="col-9">
                            @switch($val->type)
                                {{--标准搜索框--}}
                                @case('input')
                                <input name="{{$val->name}}" placeholder="{{$val->placeholder}}" value="{{$val->value??''}}" type="text" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                @break
                                {{--列表选择--}}
                                @case('select')
                                @php($options = adminHtml('search',$val->rule,'select'))
                                @if(($val->where) == 'range')
                                    <div class="row range">
                                        <div class="col-6">
                                            <select name="{{$val->name}}__start" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                                <option value="">{{__('admin.preset.search.all')}}</option>
                                                @foreach($options as $k => $o)
                                                    <option @if(($val->value[0]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="{{$val->name}}__end" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                                <option value="">{{__('admin.preset.search.all')}}</option>
                                                @foreach($options as $k => $o)
                                                    <option @if(($val->value[1]??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <select name="{{$val->name}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        <option value="">{{__('admin.preset.search.all')}}</option>
                                        @foreach($options as $k => $o)
                                            <option @if(($val->value??null) === $k) selected @endif value="{{$k}}">{!!$o!!}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @break
                                {{--数字和范围--}}
                                @case('num')
                                @if(($val->where) == 'range')
                                    <div class="row range">
                                        <div class="col-6">
                                            <input name="{{$val->name}}__start" placeholder="{{$val->placeholder}}" value="{{$val->value[0]??''}}" type="number" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="{{$val->name}}__end" placeholder="{{$val->placeholder}}" value="{{$val->value[1]??''}}" type="number" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                    </div>
                                @else
                                    <input name="{{$val->name}}" placeholder="{{$val->placeholder}}" value="{{$val->value??''}}" type="number" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                @endif
                                @break
                                {{--时间和范围--}}
                                @case('time')
                                @if(($val->where) == 'range')
                                    <div class="row range">
                                        <div class="col-6">
                                            <input name="{{$val->name}}__start" placeholder="{{$val->placeholder}}" value="{{$val->value[0]??''}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="{{$val->name}}__end" placeholder="{{$val->placeholder}}" value="{{$val->value[1]??''}}" class="form-control" {!!$val->attrHtml!!} style="{{$val->style}}">
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
                <label class="row">
                    <div class="col-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">{{__('admin.preset.search.submit')}}</button>
                        <button type="reset" class="btn btn-secondary">{{__('admin.preset.search.reset')}}</button>
                    </div>
                </label>
            </div>
        </form>
    @endif
</div>
