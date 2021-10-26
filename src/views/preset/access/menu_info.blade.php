@extends('admin::layout.vessel.layer')
@section('container.content')
    @php($upId = arguer('upId'))
    <div class="card-body layer-card-bg">
        <form id="validate">
            <div class="form-group">
                <label>{{$access->getLang('type')}}</label>
                <div class="check-group">
                    @foreach($store['type'] as $key => $val)
                        <label><input name="type" class="radio" type="radio" value="{{$key}}" @if($key == ($info->type ?? 'page')) checked @endif >{{$val}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('parent')}}</label>
                @if(!$upId)
                    <select name="upId" class="form-control select">
                        <option value="0" @if(($info ?? null) && !$info->upId) selected @endif>{{$access->getLang('null_parent')}}</option>
                        @foreach($store['parent'] as $key => $val)
                            @if($val->type != 'action' && !$val->system)
                                <option value="{{$val->mid}}" @if(($info ?? null) && $val->mid == $info->upId) selected @endif>{!!$val->deepTitle!!}</option>
                            @endif
                        @endforeach
                    </select>
                @else
                    @foreach($store['parent'] as $key => $val)
                        @if($val->mid == $upId)
                            <input type="hidden" name="upId" class="form-control" value="{{$val->mid}}"/>
                            <input type="text" class="form-control" value="{{$val->title}}" readonly/>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="form-group">
                <label>{{$access->getLang('title')}}</label>
                <input type="text" name="title" class="form-control" placeholder="{{$access->getLang('enter_title')}}" value="{{$info->title ?? ''}}"/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('icon')}}</label>
                <div class="input-group">
                    <input type="text" name="icon" class="form-control" placeholder="{{$access->getLang('enter_icon')}}" value="{{$info->icon ?? ''}}"/>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button">{{$access->getLang('icon_button')}}</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('path')}}</label>
                <input type="text" name="path" class="form-control" placeholder="{{$access->getLang('enter_path')}}" value="{{$info->path ?? ''}}"/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('remark')}}</label>
                <input type="text" name="remark" class="form-control" placeholder="{{$access->getLang('enter_remark')}}" value="{{$info->remark ?? ''}}"/>
            </div>
            <input type="hidden" name="weight" class="form-control" value="{{$info->weight ?? 0}}"/>
        </form>
    </div>
@endsection
@section('container.script')
    <script>
        var menuType;
        var menuChange = function(type){
            menuType = type;
            if(menuType == 'menu') $('#validate [name="path"]').parent().hide();
            else $('#validate [name="path"]').parent().show();
            if(menuType == 'action') $('#validate [name="icon"]').parent().parent().hide();
            else $('#validate [name="icon"]').parent().parent().show();
        };
        menuChange('{{$info->type ?? 'page'}}');
        $('#validate [name="type"]').change(function(){
            var type = $('[name="type"]:checked').val();
            if(!type) return;
            menuChange(type);
        });
        $('#validate [name="icon"]').parent().find('button').on('click', function(){
            $.admin.layer.fontAwesome({list:'{{admin_static('libs/font-awesome/5.15.1/css/all.min.css')}}'}, function(index, layero){
                $('#validate [name="icon"]').val(layero.iconClass);
                $.admin.layer.close(index);
            });
        });
        $.admin.form('#validate', {
            render:true,
            list:{
                title:{required:false, function:{rule:function(e){return menuType != 'action' ? $(e).val().length : true; }}},
                path:{required:false, function:{rule:function(e){return menuType != 'menu' ? $(e).val().length : true; }}}
            },
            callback:{
                success:function(e){
                    $.admin.api.report('loading', true);
                    $.post('', {data:e.value()}, $.admin.api.report).fail($.admin.api.report);
                }
            }
        });
    </script>
@endsection
