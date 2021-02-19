@extends('admin::layout.vessel.table')
@section('table.content')
    {!!$dbTable->html()!!}
@endsection
@section('table.script')
    <script>
        $(function(){
            $.admin.table.onPostBody = function(data, table){
                $('tbody tr[data-index]', table.$el).each(function(i){if(data[i].system) $('input[type="checkbox"]', this).prop('disabled', true);});
            };
            $('body').on('toolbar:action', function(e, a){
                switch(a.action){
                    case 'add':
                        $.admin.api.open($(a.$elem).attr('modal'), '{{$dbTable->access->path('add')}}', function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('add_role_success')}}');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'batch':
                        var rids = $.admin.table.getBatch(true);
                        if(!rids.length) return $.admin.api.fail('{{$dbTable->access->getLang('batch_none')}}');
                        var action = a.$elem.attr('action');
                        var index = 0;
                        var success = function(data){
                            $.admin.api.success(data, function(){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('batch_success')}}');
                                $.admin.table.method('refresh');
                            });
                        };
                        var title = $.admin.lang.format('{{$dbTable->access->getLang('batch_confirm')}}', {action:a.$elem.text(), length:rids.length});
                        switch(action){
                            case 'on':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {rid:rids, status:1}, success).fail($.admin.api.fail);});
                                break;
                            case 'off':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {rid:rids, status:0}, success).fail($.admin.api.fail);});
                                break;
                            case 'del':
                                index = $.admin.layer.confirm(title, function(){$.post('{{$dbTable->access->path('del')}}', {rid:rids}, success).fail($.admin.api.fail);});
                                break;
                        }
                        break;
                }
            });
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'status':
                        $.post('{{$dbTable->access->path('status')}}', {rid:a.row.rid, status:a.switch}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                    case 'edit':
                        $.admin.api.open(a.$elem.attr('modal') + ' : ' + a.row.name, '{{$dbTable->access->path('edit')}}?rid=' + a.row.rid, function(index, layer){layer.iframe.$('form').submit();}, function(index, report){
                            if(report.status){
                                $.admin.layer.close(index);
                                $.admin.alert.success('{{$dbTable->access->getLang('edit_role_success')}}');
                                $.admin.table.method('refresh');
                                return true;
                            }else $.admin.api.fail(report.xhr);
                        });
                        break;
                    case 'del':
                        $.admin.layer.confirm(a.$elem.attr('modal') + ' : ' + a.row.name, function(index){
                            $.post('{{$dbTable->access->path('del')}}', {rid:a.row.rid}, function(data){
                                $.admin.api.success(data, function(){
                                    $.admin.layer.close(index);
                                    $.admin.alert.success('{{$dbTable->access->getLang('del_role_success')}}');
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
