@extends('v1.layouts.layout')

@section('title')
  Reports
@stop

@section('content')
  <div ng-controller="ChartController" ng-init="init()" class="row">
    <div id="chart_container" class="col-md-12"></div>
  </div>
@stop