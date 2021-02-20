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
                    case 'export':
                        window.open($.admin.table.getUrl('export'));
                        break;
                    case 'clear':
                        $.admin.layer.confirm(a.$elem.attr('modal'), function(index){
                            $.post('{{$dbTable->access->path('clear')}}', function(data){
                                $.admin.table.report(data, '{{$dbTable->access->getLang('clear_log_success')}}', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                    case 'del':
                        var lids = $.admin.table.getBatch(true);
                        if(!lids.length) return $.admin.api.fail('{{$dbTable->access->getLang('batch_none')}}');
                        $.admin.layer.confirm($.admin.lang.format('{{$dbTable->access->getLang('batch_confirm')}}', {action:'{{$dbTable->access->getLang('batch_del')}}', length:lids.length}), function(index){
                            $.post('{{$dbTable->access->path('del')}}', {lid:lids}, function(data){
                                $.admin.table.report(data, '{{$dbTable->access->getLang('batch_success')}}', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
            $('body').on('row:action', function(e, a){
                switch(a.action){
                    case 'info':
                        $.admin.table.layer(a.$elem.attr('modal'), '{{$dbTable->access->path('info')}}?lid=' + a.row.lid);
                        break;
                    case 'del':
                        $.admin.layer.confirm(a.$elem.attr('modal'), function(index){
                            $.post('{{$dbTable->access->path('del')}}', {lid:a.row.lid}, function(data){
                                $.admin.table.report(data, '{{$dbTable->access->getLang('del_log_success')}}', index);
                            }).fail($.admin.api.fail);
                        });
                        break;
                }
            });
        });
    </script>
@endsection
