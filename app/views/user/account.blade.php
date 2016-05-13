@extends('layouts.layout')

@section('title')
  My Account
@stop
@section('content')
  @if(Session::has('success'))
    <div class="alert alert-success">
      <h2><% Session::get('success') %></h2>
    </div>
  @endif
  @if ($errors->has())
    <div class="alert alert-danger">
      <ul class="list-unstyled">
        @foreach ($errors->all() as $error)
          <li><% $error %></li>
        @endforeach
      </ul>
    </div>
  @endif
  <% Form::model($user, ['class' => 'form-horizontal']) %>
  <div class="form-group">
    <div class="col-sm-2 control-label">
      <% Form::label('username', 'Username') %>
    </div>
    <div class="col-sm-4">
      <% Form::text('username', null, ['class' => 'form-control', 'disabled' => true]) %>
    </div>
    <div class="@if ($errors->has('email')) has-error @endif">
      <div class="col-sm-2 control-label">
        <% Form::label('email', 'Email Address') %>
      </div>
      <div class="col-sm-4">
        <% Form::email('email', null, ['class' => 'form-control']) %>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="@if ($errors->has('firstname')) has-error @endif">
      <div class="col-sm-2 control-label">
        <% Form::label('firstname', 'First Name') %>
      </div>
      <div class="col-sm-4">
        <% Form::text('firstname', null, ['class' => 'form-control']) %>
      </div>
    </div>
    <div class="@if ($errors->has('lastname')) has-error @endif">
      <div class="col-sm-2 control-label">
        <% Form::label('lastname', 'Last Name') %>
      </div>
      <div class="col-sm-4">
        <% Form::text('lastname', null, ['class' => 'form-control']) %>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="@if ($errors->has('current_password')) has-error @endif">
      <div class="col-sm-2 control-label">
        <% Form::label('current_password', 'Current Password') %>
      </div>
      <div class="col-sm-4">
        <% Form::password('current_password', ['class' => 'form-control']) %>
      </div>
    </div>
    <div class="@if ($errors->has('password')) has-error @endif">
      <div class="col-sm-2 control-label">
        <% Form::label('password', 'New Password') %>
      </div>
      <div class="col-sm-4">
        <% Form::password('password', ['class' => 'form-control']) %>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12">
      <input type="submit" class="btn btn-primary pull-right" value="Save" />
    </div>
  </div>
  <% Form::close() %>
@stop