@extends('admin::layout.vessel.blank')
@section('container.content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-2">
                <div class="card-body">
                    <h4 class="header-title">{{app('admin')->__('base.auth.setting')}}</h4>
                    <form id="validate">
                        <div class="form-group">
                            <label>{{$access->getLang('username')}}</label>
                            <input type="text" name="username" class="form-control" value="{{$user->username ?? ''}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label>{{$access->getLang('avatar')}}</label>
                            <input type="hidden" name="avatar" value="{{$user->avatar ?? ''}}"/>
                            <div class="check-group">
                                <img class="img-thumbnail img-thumbnail-md img-thumbnail-edit rounded-circle image" src="{{app('admin')->getUserAvatar($user->avatar ?? '', $user->nickname ?? 'demon' . mstime())}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{$access->getLang('role')}}</label>
                            <select name="role" class="form-control select" multiple readonly>
                                @foreach($store['role'] as $role)
                                    <option value="{{$role['rid']}}" @if(in_array($role['rid'], explode(',', $user->role ?? ''))) selected @endif>{{$role['deepName']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{$access->getLang('nickname')}}</label>
                            <input type="text" name="nickname" class="form-control" placeholder="{{$access->getLang('enter_nickname')}}" value="{{$user->nickname ?? ''}}"/>
                        </div>
                        <div class="form-group">
                            <label>{{$access->getLang('password')}}</label>
                            <input type="password" name="password" class="form-control" placeholder="{{$access->getLang('enter_password_edit')}}"/>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">{{app('admin')->__('base.auth.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-2">
                <div class="card-body">
                    <h4 class="header-title">{{app('admin')->__('base.auth.notifications')}}</h4>
                    @if($notifications = app('admin')->getNotification())
                        <div class="inbox-wid scroll" style="max-height:500px">
                            @foreach($notifications as $val)
                                <a href="javascript:">
                                    <div class="inbox-item">
                                        <div class="inbox-item-icon bg-{{$val['theme']}}"><i class="{{$val['icon']}}"></i></div>
                                        <h6 class="mt-0 mb-1">{{$val['title']}}</h6>
                                        <p class="text-muted mb-0">{{$val['content']}}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="inbox-wid">
                            <div class="inbox-item">{{app('admin')->__('base.auth.notifications_none')}}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    <script>
        $('.inbox-wid.scroll').slimscroll({height:'auto', position:'right', size:'5px', color:'#9ea5ab', touchScrollStep:50});
        $('#validate .image').click(function(){
            var self = $(this);
            $.admin.layer.avatar({image:self.attr('src')}, function(index, o){
                $.admin.layer.close(index);
                var base64 = o.cropper.getCroppedCanvas({width:128, height:128}).toDataURL('image/jpeg', 0.6);
                self.attr('src', base64);
                $('#validate [name="avatar"]').val(base64);
            });
        });
        $.admin.form('#validate', {
            render:true,
            list:{nickname:true},
            callback:{
                success:function(e){
                    $.post('{{admin_url('auth/setting')}}', {data:e.value()}, function(data){
                        $.admin.api.success(data, function(data){$.admin.alert.success(data.message);});
                    }).fail($.admin.api.report);
                }
            }
        });
    </script>
@endsection
