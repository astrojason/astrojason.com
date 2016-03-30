@extends('layouts.layout')

@section('title')
  Games
@stop

@section('content')
  <div ng-controller="GameController" ng-init="setPlatforms(<% $platforms %>)">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_games" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>Games<button class="btn btn-success btn-xs pull-right" ng-click="gameModalOpen = true">Add</button></th>
            </tr>
            <tr>
              <th class="input-group">
                <input type="text" ng-model="game_query" class="form-control" placeholder="Search Query" />
                <div class="input-group-addon"><input type="checkbox" ng-model="include_completed" /> <label>Include completed</label></div>
              </th>
            </tr>
            <tr>
              <th class="input-group">
                <div class="input-group-addon">Platform</div>
                <select ng-model="filter_platform" class="form-control">
                  <option value="">All</option>
                  <option ng-repeat="platform in platforms">{{ platform }}</option>
                </select>
              </th>
            </tr>
            <tr>
              <th>
                <div ng-class="!filter_platform ? 'col-md-3' : 'col-md-9'">
                  <a href="#" ng-click="toggleSort('title')">Title</a>
                </div>
                <div class="col-md-6" ng-show="!filter_platform">
                  <a href="#" ng-click="toggleSort('platform')">Platform</a>
                </div>
                <div class="col-md-3">
                  <a href="#" ng-click="toggleSort('times_recommended')">Times Recommended</a>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="game_query && games.length == 0 && !loading_games">
              <td>No results for <strong>{{ game_query }}</strong>
            </tr>
            <tr ng-repeat="game in games" ng-class="{read: game.completed}">
              <td ng-class="{new: game.new}"><game-form game="game" editing="false" show-platform="!filter_platform"></game-form></td>
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
    <game-modal></game-modal>
  </div>
@stop
