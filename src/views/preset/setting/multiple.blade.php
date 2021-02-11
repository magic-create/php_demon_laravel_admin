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
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($modules as $module => $name)
                            <li class="nav-item">
                                <a class="nav-link {{array_keys($modules)[0] == $module ? 'active' : ''}}" data-toggle="tab" href="#setting-{{$module}}" role="tab" aria-selected="true">
                                    <span>{{$name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content p-1 pt-3">
                        @foreach($modules as $module => $name)
                            <div id="setting-{{$module}}" class="tab-pane {{array_keys($modules)[0] == $module ? 'active' : ''}}">
                                @include('admin::preset.setting.form', compact('module', 'list'))
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    @yield('setting.script')
@endsection
