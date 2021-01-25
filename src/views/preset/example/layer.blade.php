@extends('admin::preset.blank')
@section('container.style')
    <style>#layer pre {max-height:480px;overflow-y:auto;margin-bottom:0;padding:10px;border-top:1px solid var(--primary);}</style>
@endsection
@section('container.content')
    <div class="col-lg-6">
        <div class="card mb-2">
            <div class="card-body">
                <h4 class="header-title">Layer Dialog</h4>
                <p class="text-muted mb-3">使用$.admin.layer（基于layer.js）</p>
                <div class="button-items">
                    <button type="button" id="alert" class="btn btn-primary waves-effect waves-light">Alert - 普通信息框</button>
                    <button type="button" id="confirm" class="btn btn-secondary waves-effect">Confirm - 询问框</button>
                    <button type="button" id="prompt" class="btn btn-success waves-effect waves-light">Prompt - 输入框</button>
                    <button type="button" id="msg" class="btn btn-info waves-effect waves-light">Msg - 提示框</button>
                    <button type="button" id="load" class="btn btn-warning waves-effect waves-light">Load - 加载层</button>
                    <button type="button" id="tab" class="btn btn-danger waves-effect waves-light">Tab - 标签层</button>
                    <button type="button" id="photos" class="btn btn-dark waves-effect waves-light">Photos - 相册层</button>
                    <button type="button" id="iframe" class="btn btn-link waves-effect">Iframe - 加载其他网页</button>
                    <button type="button" id="media" class="btn btn-light waves-effect">Media - 多媒体</button>
                    <button type="button" id="avatar" class="btn btn-primary waves-effect waves-float">Avatar - 头像裁剪</button>
                    <button type="button" id="cropper" class="btn btn-secondary waves-effect waves-float">Cropper - 图片裁剪</button>
                    <button type="button" id="image" class="btn btn-info waves-effect waves-float">Image - 选择图片</button>
                </div>
                <h4 class="header-title mt-2">部分代码展示</h4>
                <div id="layer">
                    <pre>$('#alert').click(function() {
    $.admin.layer.alert('Alert - 普通信息框', {
        title: 'Alert',
        icon: 0,
        anim: 1
    });
});

$('#confirm').click(function() {
    $.admin.layer.confirm('Confirm - 询问框', {
        title: 'Confirm',
        icon: 3,
        anim: 0,
        yes: function() {
            $.admin.layer.msg('OK');
        }
    });
});

$('#prompt').click(function() {
    $.admin.layer.prompt({
        title: 'Prompt',
        anim: 2
    },function(value) {
        $.admin.layer.alert(value);
    });
});

$('#msg').click(function() {
    $.admin.layer.msg('Msg - 提示框', {
        icon: 1,
        anim: 3
    });
});

$('#load').click(function() {
    $.admin.layer.load(1, {
        time: 3000
    });
});

$('#iframe').click(function() {
    $.admin.layer.open({
        title: 'Iframe',
        type: 2,
        maxmin: true,
        resize: true,
        content: location.href,
        area: ['375px', '667px'],
        anim: 6
    });
});

$('#media').click(function() {
    $.admin.layer.open({
        title: false,
        type: 2,
        closeBtn: 0,
        shade: 0.8,
        content: '//player.youku.com/embed/XNDA5NzM5MTY1Ng',
        shadeClose: true,
        area: ['630px', '360px'],
        anim: 5
    });
});
                    </pre>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-2">
            <div class="card-body">
                <h4 class="header-title">More Bootstrap</h4>
                <p class="text-muted mb-3">扩展弹出层（基于bootstrap）</p>
                <div class="button-items">
                    <button type="button" id="bs-open" class="btn btn-outline-primary waves-effect waves-light">Open - 弹出Modal</button>
                    <button type="button" id="bs-alert" class="btn btn-outline-secondary waves-effect">Alert - 普通信息框</button>
                    <button type="button" id="bs-confirm" class="btn btn-outline-success waves-effect waves-light">Confirm - 询问框</button>
                    <button type="button" id="bs-prompt" class="btn btn-outline-info waves-effect waves-light">Prompt - 输入框</button>
                </div>
                <div class="button-items">
                    <button type="button" id="alert-primary" class="btn btn-outline-primary waves-effect waves-float">Primary - 提示信息</button>
                    <button type="button" id="alert-secondary" class="btn btn-outline-secondary waves-effect waves-float">Secondary - 提示信息</button>
                    <button type="button" id="alert-success" class="btn btn-outline-success waves-effect waves-float">Success - 提示信息</button>
                    <button type="button" id="alert-danger" class="btn btn-outline-danger waves-effect waves-float">Danger - 提示信息</button>
                    <button type="button" id="alert-warning" class="btn btn-outline-warning waves-effect waves-float">Warning - 提示信息</button>
                    <button type="button" id="alert-info" class="btn btn-outline-info waves-effect waves-float">Info - 提示信息</button>
                    <button type="button" id="alert-light" class="btn btn-outline-light waves-effect waves-float">Light - 提示信息</button>
                    <button type="button" id="alert-dark" class="btn btn-outline-dark waves-effect waves-float">Dark - 提示信息</button>
                </div>
                <h4 class="header-title mt-2">部分代码展示</h4>
                <div id="layer">
                    <pre>$('#bs-open').click(function() {
    $.admin.modal.open({
        title: 'Test-' + $(this).attr('id'),
        content: $(this).text(),
        size: 'lg'
    });
});

$('#bs-alert').click(function() {
    $.admin.modal.alert($(this).text(), {
        title: 'Test-' + $(this).attr('id'),
        size: 'sm'
    });
});

$('#bs-confirm').click(function() {
    $.admin.modal.confirm($(this).text(), {
        title: 'Test-' + $(this).attr('id'),
        isCenter: true,
        shadeClose: false
    },function() {
        $.admin.layer.msg('OK');
    });
});

$('#bs-prompt').click(function() {
    $.admin.modal.prompt({
        title: 'Test-' + $(this).attr('id'),
        fade: false,
        shade: false
    },function(value) {
        $.admin.layer.alert(value);
    });
});

$('#alert-primary').click(function() {
    $.admin.alert.primary($(this).text());
});

$('#alert-secondary').click(function() {
    $.admin.alert.secondary($(this).text() + ' : open', {
        time: 5e3,
        pos: 'b'
    },function() {
        $.admin.alert.primary($(this).text() + ' : close');
    }.bind(this));
});

$('#alert-success').click(function() {
    $.admin.alert.success($(this).text(), {
        pos: 'c'
    });
});

$('#alert-danger').click(function() {
    $.admin.alert.danger($(this).text(), {
        pos: 'l'
    });
});

$('#alert-warning').click(function() {
    $.admin.alert.warning($(this).text(), {
        pos: 'r'
    });
});

$('#alert-info').click(function() {
    $.admin.alert.info($(this).text(), {
        pos: 'lt'
    });
});

$('#alert-light').click(function() {
    $.admin.alert.light($(this).text(), {
        pos: 'rt'
    });
});

$('#alert-dark').click(function() {
    $.admin.alert.dark($(this).text(), {
        pos: 'rb'
    });
});
                    </pre>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    <script>
        //  Lyaer
        $('#alert').click(function(){$.admin.layer.alert($(this).text(), {title:'Test-' + $(this).attr('id'), icon:0, anim:1});});
        $('#confirm').click(function(){$.admin.layer.confirm($(this).text(), {title:'Test-' + $(this).attr('id'), icon:3, anim:0, yes:function(){$.admin.layer.msg('OK');}});});
        $('#prompt').click(function(){$.admin.layer.prompt({title:'Test-' + $(this).attr('id'), anim:2}, function(value){$.admin.layer.alert(value);});});
        $('#msg').click(function(){$.admin.layer.msg($(this).text(), {icon:1, anim:3});});
        $('#load').click(function(){$.admin.layer.load(1, {time:3000});});
        $('#tab').click(function(){
            $.admin.layer.tab({
                area:['600px', '300px'],
                anim:4,
                tab:[
                    {title:'TAB-1', content:'<div class="p-3">TAB-1-CONTENT</div>'},
                    {title:'TAB-2', content:'<div class="p-3">TAB-2-CONTENT</div>'},
                    {title:'TAB-3', content:'<div class="p-3">TAB-3-CONTENT</div>'}
                ]
            });
        });
        var avatar = function(){
            var list = [];
            for(var i = 1; i <= 5; i++) list.push({alt:'avatar-' + i, pid:i, src:'/static/admin/images/avatar/' + i + '.jpg'});
            return list;
        };
        $('#photos').click(function(){ $.admin.layer.photos({anim:5, photos:{title:'Photos', data:avatar()}}); });
        $('#iframe').click(function(){ $.admin.layer.open({title:'Test-' + $(this).attr('id'), type:2, maxmin:true, resize:true, content:location.href, area:['375px', '667px'], anim:6}); });
        $('#media').click(function(){ $.admin.layer.open({title:false, type:2, closeBtn:0, shade:0.8, content:'//player.youku.com/embed/XNDA5NzM5MTY1Ng', shadeClose:true, area:['630px', '360px'], anim:5}); });
        $('#avatar').click(function(){
            $.admin.layer.avatar({image:'/static/admin/images/avatar/1.jpg', lock:false}, function(index, o){
                $.admin.modal.alert('<div style="margin:0 auto;width:100px;"><img class="rounded" style="width:100%;height:100%" src="' + o.cropper.getCroppedCanvas({width:256, height:256}).toDataURL() + '"></div>', {title:'头像裁剪结果'});
                $.admin.layer.close(index);
            });
        });
        $('#cropper').click(function(){
            $.admin.layer.cropper({image:'/static/admin/images/avatar/9.jpg', lock:false}, function(index, o){
                $.admin.modal.alert('<div style="margin:0 auto;width:320px;max-height:500px;text-align:center"><img class="rounded" style="max-width:80%;" src="' + o.cropper.getCroppedCanvas().toDataURL() + '"></div>', {title:'图片裁剪结果'});
                $.admin.layer.close(index);
            });
        });
        $('#image').click(function(){
            $.admin.layer.image({image:'{{url('/static/admin/images/avatar/9.jpg')}}', dump:'{{admin_url('extend/image/url')}}', cropper:true, dispose:false}, function(index, o){
                $.admin.modal.alert('<div style="margin:0 auto;width:320px;max-height:500px;text-align:center"><img class="rounded" style="max-width:80%;" src="' + o.cropper.getCroppedCanvas().toDataURL() + '"></div>', {title:'图片裁剪结果'});
                $.admin.layer.close(index);
            });
        });
        //  Bootstrap
        $('#bs-open').click(function(){$.admin.modal.open({title:'Test-' + $(this).attr('id'), content:$(this).text(), size:'lg'});});
        $('#bs-alert').click(function(){$.admin.modal.alert($(this).text(), {title:'Test-' + $(this).attr('id'), size:'sm'});});
        $('#bs-confirm').click(function(){$.admin.modal.confirm($(this).text(), {title:'Test-' + $(this).attr('id'), isCenter:true, shadeClose:false}, function(){$.admin.layer.msg('OK');});});
        $('#bs-prompt').click(function(){$.admin.modal.prompt({title:'Test-' + $(this).attr('id'), fade:false, shade:false}, function(value){$.admin.layer.alert(value);});});
        $('#alert-primary').click(function(){ $.admin.alert.primary($(this).text()); });
        $('#alert-secondary').click(function(){ $.admin.alert.secondary($(this).text() + ' : open', {time:5e3, pos:'b'}, function(){$.admin.alert.primary($(this).text() + ' : close');}.bind(this)); });
        $('#alert-success').click(function(){ $.admin.alert.success($(this).text(), {pos:'c'}); });
        $('#alert-danger').click(function(){ $.admin.alert.danger($(this).text(), {pos:'l'}); });
        $('#alert-warning').click(function(){ $.admin.alert.warning($(this).text(), {pos:'r'}); });
        $('#alert-info').click(function(){ $.admin.alert.info($(this).text(), {pos:'lt'}); });
        $('#alert-light').click(function(){ $.admin.alert.light($(this).text(), {pos:'rt'}); });
        $('#alert-dark').click(function(){ $.admin.alert.dark($(this).text(), {pos:'rb'}); });
    </script>
@endsection
