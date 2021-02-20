{{--引用布局--}}
@extends('admin::layout.vessel.table')
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
@section('table.content')
    {!!$dbTable->html()!!}
@endsection
{{--加载脚本--}}
@section('table.script')
    <script>
        //  搜索面板创建完毕
        $('body').on('search:created', 'form', function(e, a){ a.$elem.find('[name="level__start"]').val(1); });
        //  页面渲染完毕
        $(function(){
            //  表格数据加载完毕
            $.admin.table.onLoadData = function(data){ $('.page-statis').html('<div>' + JSON.stringify(data.statis) + '</div>'); };
            //  工具栏点击
            $('body').on('toolbar:action', function(e, a){
                switch(a.action){
                    //  服务端导出
                    case 'export':
                        window.open($.admin.table.getUrl('export'));
                        break;
                    //  新增用户
                    case 'add':
                        $.admin.table.form('新增用户', '{{url()->current()}}?_action=add', {
                            report:function(report, index){
                                $.admin.alert.success(report.data.message);
                                $.admin.layer.close(index);
                                return true;
                            }
                        });
                        break;
                    //  批量调整
                    case 'batch':
                        var uids = $.admin.table.getBatch(true);
                        if(!uids.length) return $.admin.api.fail('未选择内容');
                        $.admin.layer.confirm('确认将所选' + uids.length + '项进行' + a.$elem.html(), function(index){
                            $.post('{{url()->current()}}', {_action:a.$elem.data('action'), uid:uids, status:a.$elem.data('value')}, function(data){
                                $.admin.table.report(data, '处理成功', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
            //  内容点击
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    //  查看受邀人
                    case 'invited':
                        $.admin.table.layer('查看用户 : ' + a.row.nickname + ' 的受邀人', '{{url()->current()}}?_action=invited&inviteUid=' + a.row.uid, {full:true});
                        break;
                    //  变更积分
                    case 'credit':
                        $.admin.api.open('变更用户 : ' + a.row.nickname + ' 的 ' + a.$elem.data('name'), '{{url()->current()}}?_action=credit&uid=' + a.row.uid + '&type=' + a.$elem.data('type'), function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('变更成功');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    //  变更状态
                    case 'status':
                        $.post('{{url()->current()}}', {_action:'status', uid:a.row.uid, status:a.switch}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                    case 'edit':
                        $.admin.api.open('编辑用户 : ' + a.row.nickname, '{{url()->current()}}?_action=edit&uid=' + a.row.uid, function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status) return $.admin.table.report(true, '编辑成功', index);
                            else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'del':
                        $.admin.layer.confirm('确认删除 : ' + a.row.nickname, function(index){
                            $.post('{{url()->current()}}', {_action:'delete', uid:a.row.uid}, function(data){
                                $.admin.api.success(data, function(){
                                    $.admin.layer.close(index);
                                    $.admin.alert.success('删除成功');
                                    $.admin.table.method('refresh');
                                });
                            }).fail($.admin.api.fail);
                        });
                        break;
                    case 'get':
                        $.admin.api.open('查看用户 : ' + a.row.nickname, '{{url()->current()}}?_action=info&uid=' + a.row.uid, {success:function(layero, index){$.admin.layer.full(index);}}, function(index){$.admin.layer.close(index);});
                        break;
                    case 'invite':
                        $.admin.layer.confirm('确认模拟 : ' + a.row.nickname + ' 的邀请注册', function(index){
                            $.post('{{url()->current()}}', {_action:'invite', inviteCode:a.row.code}, function(data){
                                $.admin.table.report(data, '注册成功', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
        });
    </script>
@endsection
