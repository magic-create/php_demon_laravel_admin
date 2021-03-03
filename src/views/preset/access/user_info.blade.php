@extends('admin::layout.vessel.layer')
@section('container.content')
    <div class="card-body layer-card-bg">
        <form id="validate">
            <div class="form-group">
                <label>{{$access->getLang('username')}}</label>
                <input type="text" name="username" class="form-control" placeholder="{{$access->getLang('enter_username')}}" value="{{$info->username ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('avatar')}}</label>
                <input type="hidden" name="avatar" value="{{$info->avatar ?? ''}}" @if($readonly ?? false) readonly @endif/>
                <div class="check-group">
                    <img class="img-thumbnail img-thumbnail-md img-thumbnail-edit rounded-circle image" src="{{app('admin')->getUserAvatar($info->avatar ?? '', $info->nickname ?? 'demon' . mstime())}}" @if($readonly ?? false) readonly @endif>
                </div>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('role')}}</label>
                <select name="role" class="form-control select" multiple @if($readonly ?? false || ($action == 'edit' && $info->system)) readonly @endif>
                    @foreach($store['role'] as $role)
                        <option value="{{$role['rid']}}" @if(in_array($role['rid'], explode(',', $info->role ?? ''))) selected @endif>{{$role['deepName']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('nickname')}}</label>
                <input type="text" name="nickname" class="form-control" placeholder="{{$access->getLang('enter_nickname')}}" value="{{$info->nickname ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('remark')}}</label>
                <input type="text" name="remark" class="form-control" placeholder="{{$access->getLang('enter_remark')}}" value="{{$info->remark ?? ''}}" @if($readonly ?? false) readonly @endif/>
            </div>
            @if($action != 'info')
                <div class="form-group">
                    <label>{{$access->getLang('password')}}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{$access->getLang($action=='add'?'enter_password':'enter_password_edit')}}" @if($readonly ?? false) readonly @endif/>
                </div>
            @endif
            @if($readonly ?? false)
                <div class="form-group">
                    <label>{{$access->getLang('activeTime')}}</label>
                    <input type="text" class="form-control" value="{{($info->activeTime ?? '') ? msdate('Y-m-d H:i:s', $info->activeTime): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>{{$access->getLang('loginTime')}}</label>
                    <input type="text" class="form-control" value="{{($info->loginTime ?? '') ? msdate('Y-m-d H:i:s', $info->loginTime): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>{{$access->getLang('createTime')}}</label>
                    <input type="text" class="form-control" value="{{($info->createTime ?? '') ? msdate('Y-m-d H:i:s', $info->createTime): ''}}" readonly/>
                </div>
                <div class="form-group">
                    <label>{{$access->getLang('updateTime')}}</label>
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
            if($('#validate [name="avatar"]').attr('readonly')) return;
            $.admin.layer.avatar({image:self.attr('src')}, function(index, o){
                $.admin.layer.close(index);
                var base64 = o.cropper.getCroppedCanvas({width:128, height:128}).toDataURL('image/jpeg', 0.6);
                self.attr('src', base64);
                $('#validate [name="avatar"]').val(base64);
            });
        });
        $.admin.form('#validate', {
            render:true,
            list:{
                username:true,
                password:'{{$action}}' == 'add'
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
