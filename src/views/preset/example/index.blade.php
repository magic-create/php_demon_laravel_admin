@extends('admin::preset.blank')
@section('container.link.before')
    <script src="//cdn.bootcdn.net/ajax/libs/echarts/5.0.1/echarts.min.js"></script>
@endsection
@section('container.style')
    <style>
        .dashboard-chart {height:300px;}
    </style>
@endsection
@section('container.content')
    {{--顶部统计--}}
    <div class="row">
        @foreach($stat as $key => $val)
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stat mb-4">
                    <div class="card-body mini-stat-img">
                        <div class="mini-stat-icon"><i class="fa fa-{{$val['icon']}} float-right"></i></div>
                        <div>
                            <h6 class="text-uppercase mb-3">{{$key}}</h6>
                            <h4 class="mb-4">{{$val['data']}}</h4>
                            @if($val['ratio'] > 0)
                                <span class="badge badge-info"> +{{$val['ratio'] * 100}}% </span>
                            @else
                                <span class="badge badge-danger"> {{$val['ratio'] * 100}}% </span>
                            @endif
                            <span class="ml-2">Compared with last month</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{--图表绘制--}}
    <div class="row">
        {{--饼图--}}
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="chart-pie" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        {{--折线图--}}
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="chart-line" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        {{--柱状图--}}
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="chart-bar" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    </div>
    {{--其他内容--}}
    <div class="row">
        {{--待办事项--}}
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mt-0 m-b-30 header-title">Todo</h4>
                    <div class="table-responsive">
                        <table class="table table-vertical">
                            <tbody>
                            @foreach($todo as $val)
                                <tr>
                                    <td>{{$val['title']}}<p class="m-0 text-muted">{{$val['tag']}}</p></td>
                                    <td align="right">
                                        <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{$val['url']}}">Go</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{--活动安排--}}
        <div class="col-xl-4 col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Activity</h4>
                    <ol class="activity-feed mb-0">
                        @foreach($activity as $val)
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date">{{date('Y-m-d H:i',$val['time'])}}</span>
                                    <span class="activity-text">{{$val['content']}}</span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                    <div class="text-center"><a href="javascript:" class="btn btn-sm btn-primary">Load More</a></div>
                </div>
            </div>
        </div>
        {{--消息--}}
        <div class="col-xl-4 col-lg-6">
            {{--公告通知--}}
            <div class="card widget-user mb-4">
                <div class="widget-user-desc p-4 text-center position-relative">
                    <h3 class="fas fa-quote-left text-white-50"></h3>
                    <p class="text-white mb-0">{{$notice['content']}}</p>
                </div>
                <a href="{{$notice['url']}}">
                    <div class="p-4">
                        <div class="float-left mt-2 mr-3"><img src="{{$notice['avatar']}}" class="rounded-circle img-thumbnail img-thumbnail-md"></div>
                        <h5 class="mt-3 mb-1">{{$notice['nickname']}}</h5>
                        <p class="text-muted mb-0">{{date('Y-m-d H:i:s',$notice['time'])}}</p>
                    </div>
                </a>
            </div>
            {{--其他消息--}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">Message</h4>
                    <div class="inbox-wid">
                        @foreach($message as $val)
                            <a href="javascript:">
                                <div class="inbox-item">
                                    <div class="float-left mr-3"><img src="{{$val['avatar']}}" class="img-thumbnail img-thumbnail-md rounded-circle"></div>
                                    <h6 class="mt-0 mb-1">{{$val['nickname']}}</h6>
                                    <p class="text-muted mb-0">{{$val['content']}}</p>
                                    <p class="inbox-item-date text-muted">{{date('Y-m-d H:i',$val['time'])}}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    <script>
        var pie = $.admin.echarts('#chart-pie', {
            title:{text:'Pie Test', subtext:'Test'},
            legend:{type:'scroll', bottom:0},
            tooltip:{renderMode:'richText'},
            series:[{
                type:'pie',
                avoidLabelOverlap:false,
                radius:['60%', '90%'],
                top:30, bottom:30,
                label:{show:false, position:'center'},
                emphasis:{label:{show:true, fontSize:30, fontWeight:'bold'}},
                data:[]
            }]
        });
        var line = $.admin.echarts($('#chart-line'), {
            title:{text:'Line Test', subtext:'Test'},
            legend:{type:'scroll', bottom:0},
            tooltip:{renderMode:'richText', trigger:'axis'},
            grid:{left:20, right:50, top:60, bottom:30, containLabel:true},
            xAxis:[{type:'category', boundaryGap:false, data:[]}],
            yAxis:[{type:'value'}],
            series:[]
        });
        var bar = $.admin.echarts($('#chart-bar'), {
            title:{text:'Line Test', subtext:'Test'},
            legend:{type:'scroll', bottom:0},
            tooltip:{renderMode:'richText', trigger:'axis', axisPointer:{type:'shadow'}},
            grid:{left:20, right:50, top:60, bottom:30, containLabel:true},
            xAxis:[{type:'category', data:[]}],
            yAxis:[{type:'value'}],
            series:[]
        });
        var refresh = function(init){
            if(init){
                pie.loading();
                line.loading();
                bar.loading();
            }
            $.get('{{url()->current()}}?_action=charts', function(data){
                var pieOption = pie.getOption();
                pieOption.series[0].data = data.data.pie;
                pie.setOption(pieOption);
                var lineOption = line.getOption();
                lineOption.xAxis[0].data = data.data.line.date;
                $.each(data.data.line.item, function(k, v){ lineOption.series[k] = {name:data.data.line.item[k], type:'line', emphasis:{focus:'series'}, areaStyle:{}, smooth:true, data:data.data.line.data[k] || []}; });
                line.setOption(lineOption);
                var barOption = bar.getOption();
                barOption.xAxis[0].data = data.data.bar.date;
                $.each(data.data.bar.item, function(k, v){ barOption.series[k] = {name:data.data.bar.item[k], type:'bar', stack:'total', emphasis:{focus:'series'}, smooth:true, data:data.data.bar.data[k] || []}; });
                bar.setOption(barOption);
                setTimeout(function(){ refresh(); }, 1000);
                if(init){
                    pie.loading(false);
                    line.loading(false);
                    bar.loading(false);
                }
            });
        };
        if(pie) refresh(true);
        $(window).resize(function(){
            pie.resize();
            line.resize();
            bar.resize();
        });
    </script>
@endsection
