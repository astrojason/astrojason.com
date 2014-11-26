@extends('layouts/layout')

@section('title')
  Welcome
@stop

@section('content')
  {{ App::environment() }}
@stop