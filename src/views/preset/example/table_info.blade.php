@extends('admin::preset.layer')
@section('container.content')
    <div class="card-body layer-card-bg">
        <form id="validate">
            <div class="form-group">
                <label>用户昵称</label>
                <input type="text" name="nickname" class="form-control" placeholder="请输入用户昵称" value="{{$info->nickname ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>生日</label>
                <input type="text" name="birthday" class="form-control date" placeholder="点击选择生日" data-format="YYYY-MM-DD" value="{{$info->birthday ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>等级</label>
                <input type="text" name="level" class="form-control slider" data-min="{{min(array_keys($store['level']))}}" data-max="{{max(array_keys($store['level']))}}" value="{{$info->level ?? 0}}"
                       @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>爱好</label>
                <select name="hobby" class="form-control select" multiple @if($readonly ?? false) readonly @endif>
                    @foreach($store['hobby'] as $key => $val)
                        <option value="{{$val->id}}" @if(in_array($val->id, $info->hobby ?? [])) selected @endif>{{$val->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>性别</label>
                <div class="check-group">
                    @foreach($store['sex'] as $key => $val)
                        <label><input name="sex" class="radio" type="radio" value="{{$key}}" @if($key == ($info->sex ?? 0)) checked @endif @if($readonly ?? false) readonly @endif>{{$val}}</label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label>简介</label>
                <textarea name="intro" class="form-control" rows="5" placeholder="请输入500字以内的简介" @if($readonly ?? false) readonly @endif>{{$info->intro ?? ''}}</textarea>
            </div>
        </form>
    </div>
@endsection
@section('container.script')
    <script>
        $.admin.form('#validate', {
            render:true,
            list:{
                nickname:true,
                birthday:true,
                intro:true
            },
            callback:{
                success:function(e){ $.post('', {uid:'{{$info->uid ?? ''}}', data:e.value()}, $.admin.api.report).fail($.admin.api.report);}
            }
        });
    </script>
@endsection
