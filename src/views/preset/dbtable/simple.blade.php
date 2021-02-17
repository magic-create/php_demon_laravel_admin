{{--引用布局--}}
@extends('admin::layout.vessel.table')
{{--渲染表格--}}
@section('table.content')
    {!!$dbTable->html()!!}
@endsection
