@extends('layouts.layout')

@section('title')
  Movies
@stop

@section('content')
  <div ng-controller="MovieController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_movies" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>Movies<button class="btn btn-success btn-xs pull-right" ng-click="movieModalOpen = true">Add</button></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input type="text" ng-model="movie_query" class="form-control" placeholder="Search Query" />
              </td>
            </tr>
            <tr ng-show="movie_query && movie.length == 0 && !loading_movies">
              <td>No results for <strong>{{ movie_query }}</strong>
            </tr>
            <tr ng-repeat="movie in movies">
              <td ng-class="{new: movie.new}"><movie-form movie="movie" editing="false"></movie-form></td>
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
