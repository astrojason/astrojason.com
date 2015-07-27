@extends('layouts.layout')

@section('title')
  Books :: GoodReads
@stop

@section('content')
  <div ng-controller="GoodReadsController" ng-init="getGoodReadsBooks()">
  </div>
@stop