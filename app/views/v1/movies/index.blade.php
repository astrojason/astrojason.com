@extends('v1.layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="MovieController">
    <div class="row" ng-show="movies.length == 0 && !loading_movies && !movie_query" ng-cloak>
      <div class="col-xs-12">
        <div class="alert alert-info text-center">
          You do not have any movies, would you like me to
          <button class="btn btn-default" ng-click="populateMovies()">
            Randomize
          </button>
          some for you?
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_movies" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>Movies<button class="btn btn-success btn-xs pull-right" ng-click="movieModalOpen = true">Add</button></th>
            </tr>
            <tr>
              <th>
                <div class="search-wrapper">
                  <input type="text" ng-model="movie_query" class="form-control" placeholder="Search Query" />
                  <small
                    class="clear-input glyphicon glyphicon-remove-circle"
                    ng-show="movie_query"
                    ng-click="movie_query = ''"></small>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="movie_query && movies.length == 0 && !loading_movies">
              <td>No results for <strong>{{ movie_query }}</strong>
            </tr>
            <tr ng-repeat="movie in movies" ng-class="{read: movie.is_watched}">
              <td ng-class="{new: movie.new}"><movie-form movie="movie"></movie-form></td>
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
    <movie-modal></movie-modal>
  </div>
@stop
