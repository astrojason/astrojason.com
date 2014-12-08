@extends('layouts/layout')

@section('title')
  Welcome
@stop

@section('content')
  @if(Auth::user())
    <div ng-controller="DashboardController">
      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>Daily <small class="pull-right" ng-class="total_read < 10 ? (total_read < 5 ? 'text-danger' : 'text-warning') : 'text-success'">@{{ total_read }} marked as read today</small></th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="link in daily_links" ng-show="!link.deleted">
            <td><link-form link="@{{ link }}"></link-form></td>
          </tr>
        </tbody>
      </table>
      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th class="input-group">
              <select name="category" ng-model="display_category" ng-init="categories = {{ $categories }}" ng-options="category for category in categories" class="form-control"></select>
              <div class="input-group-addon"><span class="glyphicon glyphicon-refresh tool" ng-click="getCategoryArticles()"></span></div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="link in selected_links" ng-class="link.times_loaded > 10 ? (link.times_loaded > 50 ? 'danger' : 'warning') : ''" ng-show="!link.deleted">
            <td><link-form link="@{{ link }}"></link-form></td>
          </tr>
        </tbody>
      </table>

      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th class="input-group">
              <input type="text" ng-model="search_query" class="form-control" />
              <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></th></div>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="link in search_results" ng-show="!link.deleted">
            <td ng-class="link.is_read == 1 ? 'read' : ''"><link-form link="@{{ link }}"></link-form></td>
          </tr>
        </tbody>
      </table>
    </div>
  @endif
@stop
