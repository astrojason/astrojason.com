@extends('layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="GameController">
    <table class="table table-condensed table-striped table-hover">
      <thead>
        <tr>
          <th>
            <input type="text" ng-model="search_query" class="form-control" placeholder="Search Query" />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr ng-show="search_query && search_results.length == 0 && !searching">
          <td>No results for <strong>{{ search_query }}</strong>
        </tr>
        <tr ng-repeat="game in search_results" ng-show="search_results.length > 0" ng-cloak>
          <td><game-form game="game" editing="false"></game-form></td>
        </tr>
      </tbody>
    </table>
  </div>
@stop
