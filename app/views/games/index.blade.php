@extends('layouts.layout')

@section('title')
  Games
@stop

@section('content')
  <div ng-controller="GameController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_games" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th class="input-group">
                <input type="text" ng-model="game_query" class="form-control" placeholder="Search Query" />
                <div class="input-group-addon"><input type="checkbox" ng-model="include_played" /> <label>Include played</label></div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="game_query && games.length == 0 && !loading_games">
              <td>No results for <strong>{{ game_query }}</strong>
            </tr>
            <tr ng-repeat="game in games">
              <td><game-form game="game" editing="false"></game-form></td>
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
