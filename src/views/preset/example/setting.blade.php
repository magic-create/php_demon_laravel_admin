{{--引用布局--}}
@extends('admin::preset.setting.multiple')
{{--扩展类型--}}
@section('setting.custom.image')
    <div class="input-group">
        <input type="hidden"/>
        <div class="check-group">
            <img class="img-thumbnail img-thumbnail-md img-thumbnail-edit rounded-circle image">
        </div>
    </div>
@endsection
{{--加载脚本--}}
@section('setting.script')
    <script>
        //  加载完毕
        $('body').on('setting:build', function(e, a){
            //  富文本
            $.admin.editor(a.selector + ' .editor', {height:800, plugins:'preview fullscreen lists advlist table autolink link hr'});
            //  Markdown
            $.admin.markdown(a.selector + ' .markdown', {height:800});
            //  自定义内容区域
            $('[setting-type="image"]').each(function(k, v){
                var data = $(v).data();
                var input = $(v).find('input[type="hidden"]');
                if(input.length){
                    input.attr('name', data.name);
                    input.attr('value', data.value);
                    input.attr('readonly', data.readonly);
                    input.attr('required', data.required);
                    var image = $('.image', $(v));
                    image.attr('src', data.value);
                    //  图片选择
                    $(image).click(function(){
                        var self = $(this);
                        if(input.attr('readonly')) return;
                        $.admin.layer.image({image:self.attr('src'), dump:'{{admin_url('extend/image/dump')}}', cropper:true}, function(index, o){
                            $.admin.layer.close(index);
                            var base64 = o.cropper.getCroppedCanvas().toDataURL('image/jpeg', 0.6);
                            self.attr('src', base64);
                            input.val(base64);
                        });
                    });
                }
            });
        });
        //  检查通过
        $('body').on('setting:form', function(e, a){
            $.admin.layer.confirm('确认保存配置', function(index){
                $.post('{{url()->current()}}', {module:a.module, data:a.value()}, function(data){
                    $.admin.api.success(data, function(){
                        $.admin.layer.close(index);
                        $.admin.alert.success('保存成功');
                    });
                }).fail($.admin.api.fail);
            });
        });
    </script>
@endsection
