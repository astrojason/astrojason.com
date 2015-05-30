@extends('layouts.layout')

@section('title')
  Links
@stop

@section('content')
  <div ng-controller="LinkController" ng-init="setCategories(<% $link_categories %>)">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_links" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>
                <input type="text" ng-model="links_query" class="form-control" placeholder="Search Query" />
              </th>
            </tr>
            <tr>
              <th class="input-group">
                <div class="input-group-addon">Category</div>
                <select name="category" ng-model="display_category" ng-options="category for category in categories" class="form-control">
                  <option value="">All</option>
                </select>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="links_query && links.length == 0 && !loading_links">
              <td>No results for <strong>{{ links_query }}</strong>
            </tr>
            <tr ng-repeat="link in links">
              <td><link-form link="link" editing="false"></link-form></td>
            </tr>
          </tbody>
          <tfoot ng-show="pages > 1">
            <tr>
              <td><paginator page="page" pages="pages" nav-pages="nav_pages"></paginator></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@stop
