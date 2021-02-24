@extends('admin::layout.vessel.container')
@section('container.link.before')
    <script src="{{admin_static('libs/bootstrap4-editormd/1.5.0/editormd.min.js')}}"></script>
@endsection
@section('container.content')
    <h4 class="mt-0 header-title">Markdown Editor</h4>
    <p class="text-muted mb-4">使用$.admin.markdown来构建（基于Editor.md）</p>
    <form id="validate">
        <div class="form-group">
            <label>简单示例</label>
            <textarea name="simple" class="form-control" rows="20" placeholder="请输入内容"></textarea>
        </div>
        <div class="form-group">
            <label>复杂示例</label>
            <textarea name="complex" class="form-control" rows="20" placeholder="请输入内容"></textarea>
        </div>
        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">提交</button>
            <button type="reset" class="btn btn-secondary waves-effect">重置</button>
        </div>
    </form>
@endsection
@section('container.script')
    <script>
        var dimage = {
            dimageChoose:{maxWidth:800, cropper:true, dispose:true, dump:'{{admin_url('extend/image/dump')}}', uploadUrl:'{{admin_url('extend/image/upload')}}'},
            dimageUploader:function(url, result, data, call){$.admin.api.upload(url, {blob:result, name:data.name, dir:'upload/admin/example/markdown/' + moment().format('YYYYMMDD')}, function(data){ call(data); });}
        };
        $.admin.markdown('#validate [name="simple"]', $.extend({height:360, watch:false}, dimage));
        $.admin.markdown('#validate [name="complex"]', $.extend({height:480, toolbarIcons:'full'}, dimage));
        $.admin.form('#validate', {
            callback:{
                success:function(e){
                    var data = e.value();
                    var html = '';
                    html += '<h5>简单示例</h5>';
                    html += data.simple;
                    html += '<hr>';
                    html += '<h5>复杂示例</h5>';
                    html += data.complex;
                    var res = $('<div>').html(marked(html));
                    res.find('table').addClass('table table-striped table-hover table-bordered');
                    $.admin.modal.open({title:'Editor', content:res.prop('outerHTML'), size:'lg'});
                }
            }
        });
    </script>
@endsection
