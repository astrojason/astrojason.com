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
              <th>Links<button class="btn btn-success btn-xs pull-right" ng-click="linkModalOpen = true">Add</button></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input type="text" ng-model="links_query" class="form-control" placeholder="Search Query" />
              </td>
            </tr>
            <tr>
              <td class="input-group">
                <div class="input-group-addon">Category</div>
                <select name="category" ng-model="display_category" ng-options="category for category in categories" class="form-control">
                  <option value="">All</option>
                </select>
              </td>
            </tr>
            <tr ng-show="links_query && links.length == 0 && !loading_links">
              <td>No results for <strong>{{ links_query }}</strong>
            </tr>
            <tr ng-repeat="link in links">
              <td ng-class="{new: link.new}"><link-form link="link" editing="false"></link-form></td>
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
    <link-modal></link-modal>
  </div>
@stop
