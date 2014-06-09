@extends('layouts/main')

@section('content')
  <div ng-controller="allLinksController as linkCtrl">
    <div class="row">
      <div class="col-md-10 col-md-offset-1 clearfix" ng-show="linkCtrl.links">
        <span class="pull-left" ng-show="filtered">@{{ filtered.length }} total links</span>
        <span class="pull-right"><input type="text" class="form-control" ng-model="filter" /></span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <table class="table table-striped all-links">
          <tr ng-repeat="link in filtered = (linkCtrl.links | filter:filter)" ng-class="link.read ? 'read' : ''" ng-include src="'js/templates/linkInfo.html'"></tr>
        </table>
      </div>
    </div>
  </div>
@stop

@section('scripts')

@stop
