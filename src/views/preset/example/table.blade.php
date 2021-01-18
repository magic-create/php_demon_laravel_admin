{{--引用布局--}}
@extends('admin::preset.container')
{{--扩展搜索--}}
@section('search.nickname.nickname')
    <input name="nickname" value="{{$dbTable->search->value->nickname??''}}" class="form-control">
@endsection
{{--扩展功能按钮--}}
@section('toolbar.button')
    <button class="btn btn-primary dropdown-toggle" id="action_batch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i> 批量设置</button>
    <div class="dropdown-menu" aria-labelledby="action_batch">
        <a class="dropdown-item" href="javascript:" data-button-key="action_batch" data-value="true">设置正常</a>
        <a class="dropdown-item" href="javascript:" data-button-key="action_batch" data-value="false">设置隐藏</a>
    </div>
@endsection
{{--渲染表格--}}
@section('container.content')
    {!!$dbTable->html()!!}
@endsection
{{--加载脚本--}}
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
                    //  批量操作
                    case 'action_batch':
                        var uids = $.admin.table.getBatch(true);
                        if(!uids.length) return $.admin.api.fail('未选择内容');
                        $.post('{{url()->current()}}', {_action:'status', uid:uids, status:a.$elem.data('value')}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                }
            });
            //  内容点击
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'add':
                        $.admin.alert.success('Add : ' + a.row.nickname, {pos:'c'});
                        break;
                    case 'edit':
                        $.admin.alert.primary('Edit : ' + a.row.nickname, {pos:'c'});
                        break;
                    case 'del':
                        $.admin.alert.danger('Del : ' + a.row.nickname, {pos:'c'});
                        break;
                    case 'get':
                        $.admin.alert.info('Get : ' + a.row.nickname, {pos:'c'});
                        break;
                    case 'test':
                        $.admin.alert.secondary('Test : ' + a.row.nickname, {pos:'c'});
                        break;
                    //  变更状态
                    case 'status':
                        $.post('{{url()->current()}}', {_action:'status', uid:a.row.uid, status:a.switch}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                }
            });
        });
    </script>
@endsection
