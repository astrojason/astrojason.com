@extends('layouts/layout')

@section('title')
  Welcome
@stop

@section('content')
  {{ App::environment() }}
  <link-form id="0"></link-form>
@stop
