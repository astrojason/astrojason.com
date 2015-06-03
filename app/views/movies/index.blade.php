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
              <th>
                <input type="text" ng-model="movie_query" class="form-control" placeholder="Search Query" />
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="movie_query && movie.length == 0 && !loading_movies">
              <td>No results for <strong>{{ movie_query }}</strong>
            </tr>
            <tr ng-repeat="movie in movies">
              <td>
                <movie-form movie="movie" editing="false"></movie-form>
              </td>
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
