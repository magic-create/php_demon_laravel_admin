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
            //  加载完毕
            $.admin.table.onDraw = function(data){ $('.page-statis').html('<div>' + JSON.stringify(data.statis) + '</div>'); };
            //  工具栏点击
            $('body').on('toolbar:action', function(e, a){
                switch(a.action){
                    //  批量调试
                    case 'batch':
                        var uids = [];
                        $.each($.admin.table.getBatch(), function(index, item){uids.push(item.uid);});
                        uids.length ? $.admin.modal.alert('选择了如下内容:\n' + uids.join(',')) : $.admin.alert.warning('未选择内容');
                        break;
                    //  服务端导出
                    case 'export':
                        window.open($.admin.table.getUrl('export'));
                        break;
                    //  扩展按钮
                    case 'custom':
                        $.admin.layer.msg('点击了扩展按钮，然后表格刷新');
                        $.admin.table.method('refresh');
                        break;
                }
            });
            //  内容点击
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'add':
                        $.admin.alert.success('Add : ' + a.row.nickname, {pos:'r'});
                        break;
                    case 'edit':
                        $.admin.alert.primary('Edit : ' + a.row.nickname, {pos:'r'});
                        break;
                    case 'del':
                        $.admin.alert.danger('Del : ' + a.row.nickname, {pos:'r'});
                        break;
                    case 'get':
                        $.admin.alert.info('Get : ' + a.row.nickname, {pos:'r'});
                        break;
                    case 'test':
                        $.admin.alert.secondary('Test : ' + a.row.nickname, {pos:'r'});
                        break;
                }
            });
        });
    </script>
@endsection