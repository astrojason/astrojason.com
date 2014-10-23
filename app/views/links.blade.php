@extends('layouts/main')

@section('content')
  <div ng-controller="allLinksController as linkCtrl">
    <div class="row">
      <div class="col-md-12 clearfix page-controls">
        <span class="pull-left" ng-show="linkCtrl.links">@{{ filtered.length }} total links</span>
        <span class="pull-right"><input type="text" class="form-control" ng-model="filter" /></span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped all-links">
          <tr ng-repeat="link in linkCtrl.links" ng-class="link.read ? 'read' : ''" ng-include src="'js/templates/linkInfo.html'"></tr>
        </table>
      </div>
    </div>
  </div>
@stop

@section('scripts')

@stop
