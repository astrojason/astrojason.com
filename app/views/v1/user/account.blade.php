@extends('v1.layouts.layout')

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
  <div class="col-sm-12">
    <hr />
  </div>
  <div class="col-sm-12">
    <h3>Dashboard Categories</h3>
    <hr />
  </div>
  <div class="form-group">
    <div class="col-sm-1"></div>
    @foreach($dashboardLayout as $dashboardCategory)
      @if($dashboardCategory->id)
        <% Form::open(['class' => 'form-horizontal', 'url' => '/account/dashboard-category/' . $dashboardCategory->id]) %>
      @else
        <% Form::open(['class' => 'form-horizontal', 'url' => '/account/dashboard-category/']) %>
      @endif
        <div class="col-sm-2">
          <div class="row">
            <input type="hidden" name="position" value="<% $dashboardCategory->position %>" />
            <div class="col-md-12">
              <select class="form-control" name="category">
                <option>Select Category</option>
                @foreach($categories as $category)
                  <option
                    @if($category == $dashboardCategory->category)
                      SELECTED
                    @endif><% $category %></option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label>Links to show:</label>
              <input
                name="num_items"
                type="number"
                class="form-control"
                value="<% $dashboardCategory->num_items > 0 ? $dashboardCategory->num_items : '' %>" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-md-offset-2">
              <label class="checkbox">
                <input
                  type="checkbox"
                  @if($dashboardCategory->randomize)
                    CHECKED
                  @endif
                  name="randomize" />
                Randomize
              </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10 col-md-offset-2">
              <label class="checkbox">
                <input
                  type="checkbox"
                  @if($dashboardCategory->track)
                    CHECKED
                  @endif
                  name="track" />
                Track
              </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" style="padding-top: 10px">
              <input type="submit" class="btn btn-primary" value="Update" />
            </div>
          </div>
        </div>
      <% Form::close() %>
    @endforeach
    <div class="col-sm-1"></div>
  </div>
@stop
