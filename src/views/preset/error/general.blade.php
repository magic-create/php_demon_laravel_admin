@extends('admin::preset.error.base')

@php($exception = $exception ?? null)
@php($code = $code ?? ($exception ? $exception->getStatusCode() : 'Unknown'))
@php($message = $message ?? ($exception ? ($exception->getMessage() ? : admin_error($code)) : 'Unknown Error'))
@section('title',$message)
@section('code', $code)
@section('message', $message)
