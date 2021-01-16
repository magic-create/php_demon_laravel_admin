@extends('admin::preset.error.base')

@section('title', __($exception->getMessage() ?: 'Unknown Error'))
@section('code', __($exception->getStatusCode() ?: 'Unknown'))
@section('message', __($exception->getMessage() ?: 'Unknown Error'))
