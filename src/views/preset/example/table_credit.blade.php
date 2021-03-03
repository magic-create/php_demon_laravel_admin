@extends('admin::layout.vessel.layer')
@section('container.content')
    <div class="card-body layer-card-bg">
        <form id="validate">
            @foreach($credit as $t => $v)
                @if($t != $type) @continue @endif
                <div class="form-group">
                    <label>当前{{$v['name']}}</label>
                    <input type="number" class="form-control" value="{{$info->{$v['alias']} ?? 0}}" readonly/>
                </div>
            @endforeach
            <div class="form-group">
                <label>变更类型</label>
                <div class="check-group">
                    <label><input name="type" class="radio" type="radio" value="0">减少</label>
                    <label><input name="type" class="radio" type="radio" value="1" checked>增加</label>
                </div>
            </div>
            <div class="form-group">
                <label>变更数量</label>
                <input type="number" name="change" class="form-control"/>
            </div>
        </form>
    </div>
@endsection
@section('container.script')
    <script>
        $.admin.form('#validate', {
            render:true,
            list:{
                change:{function:{rule:function(e){return $(e).val() > 0; }, message:'请输入正确的变更数量'}}
            },
            callback:{
                success:function(e){
                    $.admin.api.report('loading', true);
                    $.post('', {change:e.value().type == 0 ? -(e.value().change) : e.value().change}, $.admin.api.report).fail($.admin.api.report);
                }
            }
        });
    </script>
@endsection
