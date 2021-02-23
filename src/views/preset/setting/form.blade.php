<form id="validate-{{$module}}">
    @foreach($list as $key => $val)
        @php($validate = implode(' ', $val['validateAttr']))
        @php($val = array_to_object($val->all()))
        @php($type = strtolower($val->type))
        @if($val->module == $module && $val->hidden != 1)
            <div class="form-group" id="setting-{{$module}}-{{$val->name}}" data-tag="{{$val->tag}}">
                <label class="d-block">{{$val->title}}<span class="float-right text-muted">{{$val->module}}.{{$val->name}}</span></label>
                @if($type === 'checkbox')
                    <div class="check-group"><input type="checkbox" class="switch" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif @if($val->value) checked @endif></div>
                @elseif($type == 'checkboxs')
                    <div class="check-group">
                        @foreach($val->data as $k => $v)
                            <label><input type="checkbox" class="checkbox" @if(!$val->hidden) name="{{$val->name}}" @else disabled @endif value="{{$k}}" @if(in_array($k,$val->value)) checked @endif>{{$v}}</label>
                        @endforeach
                    </div>
                @elseif($type == 'radio')
                    <div class="check-group">
                        @foreach($val->data as $k => $v)
                            <label><input type="radio" class="radio" @if(!$val->hidden) name="{{$val->name}}" @else disabled @endif value="{{$k}}" @if($val->value == $k) checked @endif>{{$v}}</label>
                        @endforeach
                    </div>
                @elseif($type == 'select')
                    <select class="form-control select" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else disabled @endif @if($val->must) required @endif>
                        @if(!$val->must)
                            <option value="" @if($val->value === '') selected @endif>&nbsp;</option>
                        @endif
                        @foreach($val->data as $k => $v)
                            <option value="{{$k}}" @if($val->value === $k) selected @endif>{{$v}}</option>
                        @endforeach
                    </select>
                @elseif($type == 'selects')
                    <select class="form-control select" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else disabled @endif multiple @if($val->must) required @endif>
                        @foreach(($val->validate->keep ?? false) ? array_replace(array_flip($val->value), $val->data) : $val->data as $k => $v)
                            <option value="{{$k}}" @if(in_array($k,$val->value)) selected @endif>{{$v}}</option>
                        @endforeach
                    </select>
                @elseif($type == 'time')
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-clock"></i></span></div>
                        <input type="text" class="form-control date" {!!$validate!!} data-format="HH:mm" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif($type == 'date')
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar-alt"></i></span></div>
                        <input type="text" class="form-control date" {!!$validate!!} data-format="YYYY-MM-DD" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif($type == 'datetime')
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar-alt"></i></span></div>
                        <input type="text" class="form-control date" {!!$validate!!} data-format="YYYY-MM-DD hh:mm" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif($type == 'color')
                    <div class="input-group color">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-palette"></i></span></div>
                        <input type="text" class="form-control" {!!$validate!!} data-format="hex" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                        <div class="input-group-append"><span class="input-group-text color-addon"></span></div>
                    </div>
                @elseif($type == 'textarea')
                    @if($val->filter == 'array')
                        <textarea class="form-control" {!!$validate!!} rows="6" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif @if($val->must) required @endif>{{implode(',',$val->value)}}</textarea>
                    @else
                        <textarea class="form-control" {!!$validate!!} rows="6" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif @if($val->must) required @endif>{{$val->value}}</textarea>
                    @endif
                @elseif($type == 'editor')
                    <textarea class="form-control editor" {!!$validate!!} rows="6" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif @if($val->must) required @endif>{{$val->value}}</textarea>
                @elseif($type == 'markdown')
                    <textarea class="form-control markdown" {!!$validate!!} rows="6" @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif @if($val->must) required @endif>{{$val->value}}</textarea>
                @elseif(in_array($type,['text','input','string','char']))
                    @if($val->filter == 'array')
                        <input type="text" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{implode(',',$val->value)}}" @if($val->must) required @endif>
                    @else
                        <input type="text" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    @endif
                @elseif($type == 'password')
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                        <input type="password" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif(in_array($type,['number','int','num','double','float']))
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calculator"></i></span></div>
                        <input type="number" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif(in_array($type,['url','link']))
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-link"></i></span></div>
                        <input type="url" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif($type == 'email')
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-at"></i></span></div>
                        <input type="email" class="form-control" {!!$validate!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    </div>
                @elseif(in_array($type,['range','between']))
                    @if($val->filter == 'array')
                        <input type="text" class="form-control slider" data-type="double" {!!$validate!!} {!!implode(' ', $val->attr)!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{implode(',',$val->value)}}" @if($val->must) required @endif>
                    @else
                        <input type="number" class="form-control slider" {!!$validate!!} {!!implode(' ', $val->attr)!!} @if(!$val->hidden) name="{{$val->name}}" @else readonly @endif value="{{$val->value}}" @if($val->must) required @endif>
                    @endif
                @else
                    <div data-name="{{$val->name}}" {!!$validate!!} data-value="{{is_array($val->value)?json_encode($val->value):$val->value}}" setting-type="{{$val->type}}" @if($val->hidden) data-readonly="true" @endif @if($val->must) data-required="true" @endif>
                        @yield("setting.custom.{$val->type}")
                    </div>
                @endif
                @if($val->tips)
                    <small class="form-text text-muted">{!!$val->tips!!}</small>
                @endif
            </div>
        @endif
    @endforeach
    <div class="form-group mb-0">
        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">{{app('admin')->__('base.auth.submit')}}</button>
        <button type="reset" class="btn btn-secondary waves-effect">{{app('admin')->__('base.auth.reset')}}</button>
    </div>
</form>
<script>
    $(function(){
        $.admin.form('#validate-{{$module}}', {
            render:true,
            callback:{
                build:function(e){
                    $('body').trigger('setting:build', [$.extend({module:'{{$module}}'}, e)]);
                },
                success:function(e){
                    $('body').trigger('setting:form', [$.extend({module:'{{$module}}'}, e)]);
                }
            }
        });
    });
</script>
