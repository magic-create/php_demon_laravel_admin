@extends('admin::layout.vessel.table')
@section('table.content')
    {!!$dbTable->html()!!}
@endsection
@section('table.script')
    <script>
        $(function(){
            $.admin.table.onDraw = function(data, table){
                $('tbody tr[data-index]', table.$el).each(function(i){if(data[table.options.dataField][i].system) $('input[type="checkbox"]', this).prop('disabled', true);});
            };
            $('body').on('toolbar:action', function(e, a){
                switch(a.action){
                    case 'add':
                        $.admin.api.open('{{$dbTable->access->getLang('add_user')}}', '{{$dbTable->access->path('add')}}', function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('add_user_success')}}');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'batch':
                        var uids = $.admin.table.getBatch(true);
                        if(!uids.length) return $.admin.api.fail('{{$dbTable->access->getLang('batch_none')}}');
                        var action = a.$elem.attr('action');
                        var index = 0;
                        var success = function(data){
                            $.admin.api.success(data, function(){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('batch_success')}}');
                                $.admin.table.method('refresh');
                            });
                        };
                        var title = $.admin.lang.format('{{$dbTable->access->getLang('batch_confirm')}}', {action:a.$elem.text(), length:uids.length});
                        switch(action){
                            case 'on':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {uid:uids, status:1}, success).fail($.admin.api.fail);});
                                break;
                            case 'off':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {uid:uids, status:0}, success).fail($.admin.api.fail);});
                                break;
                            case 'del':
                                index = $.admin.layer.confirm(title, function(){$.post('{{$dbTable->access->path('del')}}', {uid:uids}, success).fail($.admin.api.fail);});
                                break;
                        }
                        break;
                }
            });
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'status':
                        $.post('{{$dbTable->access->path('status')}}', {uid:a.row.uid, status:a.switch}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                    case 'edit':
                        $.admin.api.open('{{$dbTable->access->getLang('edit_user')}} : ' + a.row.nickname, '{{$dbTable->access->path('edit')}}?uid=' + a.row.uid, function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('edit_user_success')}}');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'info':
                        $.admin.api.open('{{$dbTable->access->getLang('user_info')}} : ' + a.row.nickname, '{{$dbTable->access->path('info')}}?uid=' + a.row.uid, {}, function(index){$.admin.layer.close(index);});
                        break;
                    case 'del':
                        $.admin.layer.confirm('{{$dbTable->access->getLang('del_user')}} : ' + a.row.nickname, function(index){
                            $.post('{{$dbTable->access->path('del')}}', {uid:a.row.uid}, function(data){
                                $.admin.api.success(data, function(){
                                    $.admin.layer.close(index);
                                    $.admin.alert.success('{{$dbTable->access->getLang('del_user_success')}}');
                                    $.admin.table.method('refresh');
                                });
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
        });
    </script>
@endsection
