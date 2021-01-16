@section('breadcrumb')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{--页面标题--}}
                <h4 class="page-title">Title</h4>
                {{--面包屑导航--}}
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:">Module</a></li>
                    <li class="breadcrumb-item"><a href="javascript:">Tab</a></li>
                    <li class="breadcrumb-item active">Page</li>
                </ol>
                <div class="page-statis d-none d-sm-block"></div>
            </div>
        </div>
    </div>
@endsection
