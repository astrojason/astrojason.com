@extends('layouts/main')

@section('content')
  {{ Form::open(array('url' => 'register','method' => 'POST')) }}
  {{ Form::label('username', 'Username') }} {{ Form::text('username', '', array('placeholder' => 'username')) }}
  {{ Form::label('password', 'Password') }} {{ Form::password('password') }}
  {{ Form::submit('Register') }}
  {{ Form::close() }}
@stop

@section('scripts')

@stop