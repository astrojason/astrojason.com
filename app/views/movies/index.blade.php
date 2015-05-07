@extends('layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="MovieController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="searching_movies" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover">
          <thead>
            <tr>
              <th>
                <input type="text" ng-model="movie_query" class="form-control" placeholder="Search Query" />
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="movie_query && movie_results.length == 0 && !searching_movies">
              <td>No results for <strong>{{ movie_query }}</strong>
            </tr>
            <tr ng-repeat="movie in movie_results" ng-show="movie_results.length > 0" ng-cloak>
              <td><movie-form movie="movie" editing="false"></movie-form></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_movies" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="all()">
          <tbody>
            <tr ng-repeat="movie in movies">
              <td>
                <movie-form movie="movie" editing="false"></movie-form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop
