@extends('layouts/layout')

@section('content')
  <div class="row" ng-controller="linksController">
    <div class="col-md-8 col-md-offset-1">
      <div class="clearfix">
        <span class="pull-left" ng-show="filtered">@{{ filtered.length }} total links</span>
        <span class="pull-right"><input type="text" ng-model="filter" /></span>
      </div>
      <table class="table table-striped">
        <tr ng-repeat="link in filtered = (links | filter:filter)" ng-include="'link-info'"></tr>
      </table>
    </div>
  </div>
@stop

@section('scripts')
  <script src="/js/controllers/linksController.js"></script>

  <script type="text/ng-template" id="link-info">
    <td class="fill-row"><a href="@{{ link.link }}" target="_blank">@{{ link.name }}</a></td>
    <td><div class="glyphicon glyphicon-pencil link-action" data-toggle="modal" data-target="#linkModal" ng-click="edit(link, $index)"></div></td>
    <td><div class="glyphicon glyphicon-off link-action" ng-click="postpone(link, $index)"></div></td>
    <td><div class="glyphicon glyphicon-ok link-action" ng-click="markAsRead(link, $index)"></div></td>
  </script>
@stop
