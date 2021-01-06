@extends('admin::preset.container')
@section('container.link')
    @php($assetUrl = config('admin.web.cdnUrl') ?: '/static/admin/libs')
@endsection
@section('search.nickname.nickname')
    <input name="nickname" value="{{$dbTable->search->value->nickname??''}}" class="form-control">
@endsection
@section('toolbar.button')
    <button class="btn btn-primary" data-button-key="custom">扩展按钮</button>
@endsection
@section('container.content')
    {!!$dbTable->html()!!}
@endsection
@section('container.script')
    <script>
        $(function(){
            //  批量调试
            $('body').on('click', '[data-button-key="batch"]', function(){
                var uids = [];
                $.each($.dbTable.getBatch(), function(index, item){uids.push(item.uid);});
                uids.length ? alert('选择了如下内容:\n' + uids.join(',')) : alert('未选择内容');
            });
            //  服务端导出
            $('body').on('click', '[data-button-key="export"]', function(){ window.open($.dbTable.getUrl('export')); });
            //  扩展按钮
            $('body').on('click', '[data-button-key="custom"]', function(){
                alert('点击了扩展按钮，然后表格刷新');
                $.dbTable.method('refresh');
            });
            //  加载完毕
            $.dbTable.onDraw = function(data){ $('.page-statis').html('<div>' + JSON.stringify(data.statis) + '</div>'); };
            //  操作事件
            $.dbTable.event['click [_action="test1"]'] = function(e, value, row){ alert('操作了' + row.uid);};
            $.dbTable.event['click [_action="test2"]'] = function(e, value, row){ alert('操作了' + row.nickname);};
        });
    </script>
@endsection
