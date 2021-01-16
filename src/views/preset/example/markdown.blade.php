@extends('admin::preset.container')
@section('container.link')
    @php($assetUrl = config('admin.web.cdnUrl') ?: '/static/admin/libs')
    <script src="{{$assetUrl}}/cropperjs/1.5.9/cropper.js"></script>
    <link href="{{$assetUrl}}/cropperjs/1.5.9/cropper.css" rel="stylesheet" type="text/css">
    <style>
        .cropper {width:100px;height:100%}
    </style>
@endsection
@section('container.content')
    <h4 class="mt-0 header-title">Markdown Editor</h4>
    <p class="text-muted mb-4">使用$.admin.markdown来构建（基于bootstrap-markdown）</p>
    <form id="validate">
        <div class="form-group">
            <label>编辑器示例</label>
            {{--            <textarea name="editor" class="form-control" rows="20" placeholder="请输入10至30个字之间的任意内容"></textarea>--}}
            <div class="row">
                <div class="col-3" style="height:200px;">
                    <img class="cropper" src="/static/admin/images/avatar/1.jpg" alt="Logo">
                </div>
                <div class="offset-1 col-3">
                    <img class="cropper" src="/static/admin/images/avatar/2.jpg" alt="Logo">
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">提交</button>
            <button type="reset" class="btn btn-secondary waves-effect">重置</button>
        </div>
    </form>
@endsection
@section('container.script')
    <script>
        $.admin.cropper('.cropper');
    </script>
@endsection
