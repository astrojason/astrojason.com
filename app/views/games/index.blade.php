@extends('layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="GameController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="searching_games" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover">
          <thead>
            <tr>
              <th>
                <input type="text" ng-model="game_query" class="form-control" placeholder="Search Query" />
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="game_query && game_results.length == 0 && !searching_games">
              <td>No results for <strong>{{ game_query }}</strong>
            </tr>
            <tr ng-repeat="game in game_results" ng-show="game_results.length > 0" ng-cloak>
              <td><game-form game="game" editing="false"></game-form></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_games" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="all()">
          <tbody>
          <tr ng-repeat="game in games">
            <td><game-form game="game" editing="false"></game-form></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop
