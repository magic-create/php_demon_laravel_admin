@extends('admin::layout.vessel.blank')
@section('container.link.before')
    <script src="{{admin_static('libs/tinymce/5.6.2/tinymce.min.js')}}"></script>
    <script src="{{admin_static('libs/bootstrap4-editormd/1.5.0/editormd.min.js')}}"></script>
@endsection
@section('container.content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-2">
                <div class="card-body">
                    @include('admin::preset.setting.form', compact('module', 'list'))
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    @yield('setting.script')
@endsection
