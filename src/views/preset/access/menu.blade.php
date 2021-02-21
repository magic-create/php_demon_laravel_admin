@extends('admin::layout.vessel.table')
@section('table.link.before')
    <script src="{{admin_cdn('TableDnD/1.0.5/jquery.tablednd.min.js')}}"></script>
    <script src="{{admin_cdn('bootstrap-table/1.18.1/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js')}}"></script>
@endsection
@section('table.content')
    {!!$dbTable->html()!!}
@endsection
@section('table.script')
    <script>
        $(function(){
            $.admin.table.onPostBody = function(data, table){
                $('tbody tr[data-index]', table.$el).each(function(i){if(data[i].system) $('input[type="checkbox"]', this).prop('disabled', true);});
                $('tbody tr[data-index] td').has('[action="weight"]').each(function(i, v){$(v).data('index', $(v).parent().data('index'));});
                if($('[data-button-key="fold"]').attr('status') == 'off') $.each(data, function(k, v){if(v.type == 'action') $('tbody tr[data-uniqueid="' + v.mid + '"]', table.$el).hide();});
            };
            $('body').on('toolbar:action', function(e, a){
                switch(a.action){
                    case 'add':
                        $.admin.table.form($(a.$elem).attr('modal'), '{{$dbTable->access->path('add')}}', {report:'{{$dbTable->access->getLang('add_menu_success')}}'});
                        break;
                    case 'batch':
                        var mids = $.admin.table.getBatch(true);
                        if(!mids.length) return $.admin.api.fail('{{$dbTable->access->getLang('batch_none')}}');
                        var action = a.$elem.attr('action');
                        var index = 0;
                        var success = function(data){
                            $.admin.table.report(data, '{{$dbTable->access->getLang('batch_success')}}', index);
                        };
                        var title = $.admin.lang.format('{{$dbTable->access->getLang('batch_confirm')}}', {action:a.$elem.text(), length:mids.length});
                        switch(action){
                            case 'on':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {mid:mids, status:1}, success).fail($.admin.api.fail);});
                                break;
                            case 'off':
                                index = $.admin.layer.confirm(title, function(){ $.post('{{$dbTable->access->path('status')}}', {mid:mids, status:0}, success).fail($.admin.api.fail);});
                                break;
                            case 'del':
                                index = $.admin.layer.confirm(title, function(){$.post('{{$dbTable->access->path('del')}}', {mid:mids}, success).fail($.admin.api.fail);});
                                break;
                        }
                        break;
                    case 'fold':
                        var status = a.$elem.attr('status');
                        var data = $.admin.table.getData();
                        if(status == 'off'){
                            a.$elem.attr('status', 'on').find('i').removeClass('fa-folder-open').addClass('fa-folder');
                            $.each(data, function(k, v){if(v.type == 'action') $('tbody tr[data-uniqueid="' + v.mid + '"]', a.table.$el).show();});
                        }else{
                            a.$elem.attr('status', 'off').find('i').removeClass('fa-folder').addClass('fa-folder-open');
                            $.each(data, function(k, v){if(v.type == 'action') $('tbody tr[data-uniqueid="' + v.mid + '"]', a.table.$el).hide();});
                        }
                        break;
                }
            });
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'status':
                        $.post('{{$dbTable->access->path('status')}}', {mid:a.row.mid, status:a.switch}, function(){$.admin.table.method('refresh');}).fail($.admin.api.fail);
                        break;
                    case 'edit':
                        $.admin.table.form(a.$elem.attr('modal') + ' : ' + (a.row.title ? a.row.title : a.row.path), '{{$dbTable->access->path('edit')}}?mid=' + a.row.mid, {report:'{{$dbTable->access->getLang('edit_menu_success')}}'});
                        break;
                    case 'add':
                        $.admin.table.form(a.$elem.attr('modal') + ' : ' + (a.row.title ? a.row.title : a.row.path), '{{$dbTable->access->path('add')}}?upId=' + a.row.mid, {report:'{{$dbTable->access->getLang('add_menu_success')}}'});
                        break;
                    case 'del':
                        $.admin.layer.confirm(a.$elem.attr('modal') + ' : ' + (a.row.title ? a.row.title : a.row.path), function(index){
                            $.post('{{$dbTable->access->path('del')}}', {mid:a.row.mid}, function(data){
                                $.admin.table.report(data, '{{$dbTable->access->getLang('del_menu_success')}}', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
            $('body').on('reorder-row.bs.table', function(e, a, d, r){
                $.post('{{$dbTable->access->path('weight')}}', {mid:d.mid, referId:r.mid}, function(){
                    $.admin.table.method('refresh');
                    $('div[role="tooltip"]', '.bootstrap-table').remove();
                }).fail($.admin.api.fail);
            });
        });
    </script>
@endsection
