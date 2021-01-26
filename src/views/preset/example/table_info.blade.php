@extends('admin::preset.layer')
@section('container.link.before')
    @php($staticUrl = config('admin.static'))
    <script src="{{$staticUrl}}/libs/tinymce/5.6.2/tinymce.min.js"></script>
@endsection
@section('container.content')
    <div class="card-body layer-card-bg">
        <form id="validate">
            <div class="form-group">
                <label>用户昵称</label>
                <input type="text" name="nickname" class="form-control" placeholder="请输入用户昵称" value="{{$info->nickname ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>手机号</label>
                <input type="text" name="phone" class="form-control" placeholder="请输入手机号" value="{{$info->phone ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>生日</label>
                <input type="text" name="birthday" class="form-control date" placeholder="点击选择生日" data-format="YYYY-MM-DD" value="{{$info->birthday ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>头像</label>
                <input type="hidden" name="avatar" value="{{$info->avatar ?? ''}}" @if($readonly ?? false) readonly @endif/>
                <div class="check-group">
                    <img class="img-thumbnail img-thumbnail-md img-thumbnail-edit rounded-circle image" src="{{$info->avatar ?? ''}}" @if($readonly ?? false) readonly @endif>
                </div>
            </div>
            <div class="form-group">
                <label>等级</label>
                <input type="text" name="level" class="form-control slider" data-min="{{min(array_keys($store['level']))}}" data-max="{{max(array_keys($store['level']))}}" value="{{$info->level ?? 0}}" @if($readonly ?? false) readonly @endif/>
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
            @if($readonly ?? false)
                @foreach($store['credit'] as $v)
                    <div class="form-group">
                        <label>{{$v['name']}}</label>
                        <input type="number" class="form-control" value="{{$info->{$v['alias']} ?? 0}}" readonly/>
                    </div>
                @endforeach
                <div class="form-group">
                    <label>活跃时间</label>
                    <input type="text" class="form-control" value="{{$info->activeTime ?? ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>签到日期</label>
                    <input type="text" class="form-control" value="{{($info->signDate ?? '') ? date('Y-m-d', strtotime($info->signDate) ): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>登录IP</label>
                    <input type="text" class="form-control" value="{{($info->loginIpv4i ?? '') ? long2ip($info->loginIpv4i): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>登录时间</label>
                    <input type="text" class="form-control" value="{{($info->loginTime ?? '') ? date('Y-m-d H:i:s', $info->loginTime): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>注册时间</label>
                    <input type="text" class="form-control" value="{{($info->createTime ?? '') ? msdate('Y-m-d H:i:s', $info->createTime): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>更新时间</label>
                    <input type="text" class="form-control" value="{{($info->updateTime ?? '') ? msdate('Y-m-d H:i:s', $info->updateTime): ''}}" readonly/>
                </div>
            @endif
        </form>
    </div>
@endsection
@section('container.script')
    <script>
        $('#validate .image').click(function(){
            var self = $(this);
            if($('#validate [name="avatar"]').attr('readonly'))
                return;
            $.admin.layer.avatar({image:self.attr('src')}, function(index, o){
                $.admin.layer.close(index);
                o.cropper.getCroppedCanvas().toBlob(function(obj){
                    $.admin.api.upload('{{admin_url('extend/image/upload')}}', {blob:obj, dir:'upload/admin/example/avatar/' + moment().format('YYYYMMDD')}, function(data){
                        self.attr('src', data.file);
                        $('#validate [name="avatar"]').val(data.file);
                    });
                }, 'image/jpg');
            });
        });
        var dimage = {
            dimageChoose:{maxWidth:480, dispose:true, uploadUrl:'{{admin_url('extend/image/upload')}}'},
            dimageUploader:function(url, result, data, call){
                $.admin.api.upload(url, {blob:result, name:data.name, dir:'upload/admin/example/table/' + moment().format('YYYYMMDD')}, function(data){ call(data); });
            }
        };
        $.admin.editor('#validate [name="intro"]', $.extend({menubar:false, height:500, toolbar:'styleselect | bullist numlist outdent indent | dimage | fullscreen'}, dimage));
        $.admin.form('#validate', {
            render:true,
            list:{
                nickname:true,
                birthday:true,
                intro:true
            },
            callback:{
                success:function(e){ $.post('', {data:e.value()}, $.admin.api.report).fail($.admin.api.report);}
            }
        });
    </script>
@endsection
