{{--引用布局--}}
@extends('admin::preset.container')
{{--扩展搜索--}}
@section('search.custom.nickname')
    <input name="nickname" value="{{$dbTable->search->value->nickname??''}}" class="form-control">
@endsection
{{--扩展功能按钮--}}
@section('toolbar.button')
    <button class="btn btn-primary dropdown-toggle" id="action_batch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i> 批量调整</button>
    <div class="dropdown-menu" aria-labelledby="action_batch">
        <a class="dropdown-item" href="javascript:" data-button-key="batch" data-action="status" data-value="true">设置正常</a>
        <a class="dropdown-item" href="javascript:" data-button-key="batch" data-action="status" data-value="false">设置隐藏</a>
        <a class="dropdown-item" href="javascript:" data-button-key="batch" data-action="delete" data-value="false">设置删除</a>
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
                    //  服务端导出
                    case 'export':
                        window.open($.admin.table.getUrl('export'));
                        break;
                    //  新增用户
                    case 'add':
                        $.admin.api.open('新增用户', '{{url()->current()}}?_action=add', function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('新增成功');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    //  批量调整
                    case 'batch':
                        var uids = $.admin.table.getBatch(true);
                        if(!uids.length) return $.admin.api.fail('未选择内容');
                        $.admin.layer.confirm('确认将所选' + uids.length + '项进行' + a.$elem.html(), function(index){
                            $.post('{{url()->current()}}', {_action:a.$elem.data('action'), uid:uids, status:a.$elem.data('value')}, function(){
                                $.admin.layer.close(index);
                                $.admin.alert.success('处理成功');
                                $.admin.table.method('refresh');
                            }).fail($.admin.api.fail);
                        });
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
                        $.admin.api.open('编辑用户 : ' + a.row.nickname, '{{url()->current()}}?_action=edit&uid=' + a.row.uid, function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('编辑成功');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'del':
                        $.admin.layer.confirm('确认删除 : ' + a.row.nickname, function(index){
                            $.post('{{url()->current()}}', {_action:'delete', uid:a.row.uid}, function(){
                                $.admin.layer.close(index);
                                $.admin.alert.success('处理成功');
                                $.admin.table.method('refresh');
                            }).fail($.admin.api.fail);
                        });
                        break;
                    case 'get':
                        $.admin.api.open('查看用户 : ' + a.row.nickname, '{{url()->current()}}?_action=info&uid=' + a.row.uid, function(index){$.admin.layer.close(index);});
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
