@extends('admin::layout.vessel.layer')
@section('container.link.before')
    <script src="{{admin_cdn('jstree/3.3.9/jstree.min.js')}}"></script>
    <link href="{{admin_cdn('jstree/3.3.9/themes/default/style.min.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('container.content')
    @php($upId = arguer('upId'))
    <div class="card-body layer-card-bg">
        <form id="validate">
            <div class="form-group">
                <label>{{$access->getLang('name')}}</label>
                <input type="text" name="name" class="form-control" placeholder="{{$access->getLang('enter_name')}}" value="{{$info->name ?? ''}}"/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('remark')}}</label>
                <input type="text" name="remark" class="form-control" placeholder="{{$access->getLang('enter_remark')}}" value="{{$info->remark ?? ''}}"/>
            </div>
            <div class="form-group">
                <label>{{$access->getLang('menu')}}</label>
                <input type="hidden" name="mids" value="{{$info->mids ?? ''}}">
                <div class="dtree"></div>
            </div>
        </form>
    </div>
@endsection
@section('container.script')
    <script>
        $.admin.form('#validate', {
            render:true,
            list:{
                name:true
            },
            callback:{
                build:function(){
                    $('.dtree').jstree({
                        themes:{stripes:true},
                        checkbox:{keep_selected_style:false},
                        types:{
                            root:{icon:'fa fa-folder-open'},
                            menu:{icon:'fa fa-folder-open'},
                            file:{icon:'fa fa-file-o'}
                        },
                        plugins:['checkbox', 'types'],
                        core:{check_callback:true, data:JSON.parse('{!!json_encode($tree)!!}')}
                    }).on('changed.jstree', function(e, data){
                        $('#validate [name="mids"]').val($.unique(data.selected.map(function(v){return v.trim();})));
                    });
                },
                success:function(e){ $.post('', {data:e.value()}, $.admin.api.report).fail($.admin.api.report);}
            }
        });
    </script>
@endsection
