@extends('layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="MovieController">
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
          <td>No results for <strong>@{{ search_query }}</strong>
        </tr>
        <tr ng-repeat="movie in search_results" ng-show="search_results.length > 0" ng-cloak>
          <td><movie-form movie="movie" editing="false"></movie-form></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-condensed table-striped table-hover" ng-init="all()">
      <tbody>
        <tr ng-repeat="movie in movies">
          <td>
            <span
              class="glyphicon glyphicon-chevron-up tool pull-left"
              ng-class="$index > 0 ? '' : 'disabled'"
              ng-click="$index > 0 ? changeRating(movie, -1) : null">
            </span>
            <span
              class="glyphicon glyphicon-chevron-down tool pull-left"
              ng-class="$index < movies.length - 1 ? '' : 'disabled'"
              ng-click="$index < movies.length - 1 ? changeRating(movie, 1) : null">
            </span>
          </td>
          <td>
            <movie-form movie="movie" editing="false"></movie-form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@stop
