@extends('layouts.layout')

@section('title')
  Reports
@stop

@section('content')
  <div ng-controller="ChartController" class="row">
    <div id="chart_container" class="col-md-12"></div>
  </div>
@stop