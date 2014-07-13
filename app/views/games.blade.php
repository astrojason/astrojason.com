@extends('layouts/main')

@section('content')
<div ng-controller="allGamesController as gameCtrl">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 clearfix" ng-show="gameCtrl.games">
      <span class="pull-left" ng-show="filtered">@{{ filtered.length }} total games</span>
      <span class="pull-right"><input type="text" class="form-control" ng-model="filter" /></span>
    </div>
  </div>
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <table class="table table-striped all-links">
        <tr ng-repeat="game in filtered = (gameCtrl.games | filter:filter)" ng-class="gameCtrl.played ? 'played' : ''">
          <td>@{{ game.title }}</td>
          <td>@{{ game.platform }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>
@stop

@section('scripts')

@stop