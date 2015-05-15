@extends('layouts.layout')

@section('title')
  Links
@stop

@section('content')
  <div ng-controller="LinkController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="searching_links" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover">
          <thead>
          <tr>
            <th>
              <input type="text" ng-model="links_query" class="form-control" placeholder="Search Query" />
            </th>
          </tr>
          </thead>
          <tbody>
          <tr ng-show="links_query && link_results.length == 0 && !searching_links">
            <td>No results for <strong>{{ links_query }}</strong>
          </tr>
          <tr ng-repeat="link in link_results" ng-show="link_results.length > 0" ng-cloak>
            <td><link-form link="link" editing="false"></link-form></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_links" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="all()">
          <thead>
            <tr>
              <td>
                <select name="category" ng-model="display_category" ng-options="category for category in categories" class="form-control">
                  <option value="">All</option>
                </select></td>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="link in links">
              <td><link-form link="link" editing="false"></link-form></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td><paginator page="page" pages="pages" nav-pages="nav_pages"></paginator></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@stop
