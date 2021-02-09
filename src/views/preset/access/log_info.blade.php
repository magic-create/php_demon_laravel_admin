@extends('admin::layout.vessel.layer')
@section('container.content')
    <div class="card-body layer-card-bg">
        <div class="form-group">
            <label>{{$access->getLang('username')}}</label>
            <input type="text" class="form-control" value="[{{$info->uid ?? ''}}]{{$info->username ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('userRemark')}}</label>
            <input type="text" class="form-control" value="{{$info->userRemark ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('createTime')}}</label>
            <input type="text" class="form-control" value="{{($info->createTime ?? '') ? msdate('Y-m-d H:i:s', $info->createTime): ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('tag')}}</label>
            <input type="text" class="form-control" value="{{$info->tag ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('method')}}</label>
            <input type="text" class="form-control" value="{{$info->method ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('path')}}</label>
            <input type="text" class="form-control" value="{{$info->path ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('content')}}</label>
            <textarea class="form-control" rows="5" readonly>{{$info->content ?? ''}}</textarea>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('arguments')}}</label>
            <textarea class="form-control" rows="3" readonly>{{$info->arguments ?? ''}}</textarea>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('remark')}}</label>
            <textarea class="form-control" rows="3" readonly>{{$info->remark ?? ''}}</textarea>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('ip')}}</label>
            <input type="text" class="form-control" value="{{$info->ip ?? ''}}" readonly/>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('userAgent')}}</label>
            <textarea class="form-control" rows="3" readonly>{{$info->userAgent ?? ''}}</textarea>
        </div>
        <div class="form-group">
            <label>{{$access->getLang('soleCode')}}</label>
            <input type="text" class="form-control" value="{{$info->soleCode ?? ''}}" readonly/>
        </div>
    </div>
@endsection
