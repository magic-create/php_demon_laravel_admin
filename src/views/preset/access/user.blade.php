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
                        $.admin.table.form($(a.$elem).attr('modal'), '{{$dbTable->access->path('add')}}', {report:'{{$dbTable->access->getLang('add_user_success')}}'});
                        break;
                    case 'batch':
                        var uids = $.admin.table.getBatch(true);
                        if(!uids.length) return $.admin.api.fail('{{$dbTable->access->getLang('batch_none')}}');
                        var action = a.$elem.attr('action');
                        var index = 0;
                        var success = function(data){
                            $.admin.table.report(data, '{{$dbTable->access->getLang('batch_success')}}', index);
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
                        $.admin.table.form($(a.$elem).attr('modal') + ' : ' + a.row.nickname, '{{$dbTable->access->path('edit')}}?uid=' + a.row.uid, {report:'{{$dbTable->access->getLang('edit_user_success')}}'});
                        break;
                    case 'info':
                        $.admin.table.layer($(a.$elem).attr('modal') + ' : ' + a.row.nickname, '{{$dbTable->access->path('info')}}?uid=' + a.row.uid);
                        break;
                    case 'del':
                        $.admin.layer.confirm($(a.$elem).attr('modal') + ' : ' + a.row.nickname, function(index){
                            $.post('{{$dbTable->access->path('del')}}', {uid:a.row.uid}, function(data){
                                $.admin.table.report(data, '{{$dbTable->access->getLang('del_user_success')}}', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
        });
    </script>
@endsection
