@section('breadcrumb')
    <div class="row">
        <div class="col-sm-12">
            @php($breadcrumb = app('admin')->getBreadcrumb())
            <div class="page-title-box">
                {{--页面标题--}}
                <h4 class="page-title">{{end($breadcrumb)}}</h4>
                {{--面包屑导航--}}
                <ol class="breadcrumb">
                    @foreach($breadcrumb as $index => $title)
                        @if($index == count($breadcrumb)-1)
                            <li class="breadcrumb-item active">{{$title}}</li>
                        @else
                            <li class="breadcrumb-item"><a href="javascript:">{{$title}}</a></li>
                        @endif
                    @endforeach
                </ol>
                <div class="page-statis d-none d-sm-block"></div>
            </div>
        </div>
    </div>
@endsection
