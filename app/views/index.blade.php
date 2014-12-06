@extends('layouts/layout')

@section('title')
  Welcome
@stop

@section('content')
  {{ App::environment() }}
  <link-form id="0"></link-form>
  @if(Auth::user()->hasRole('admin'))
    <div ng-controller="AdminController">
      <button class="btn btn-toolbar" ng-click="migrateLinks()">Migrate Links</button>
    </div>
  @endif
@stop
