@extends('layouts.layout')

@section('title')
  Songs
@stop

@section('content')
  <div ng-controller="SongController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_songs" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>
                <input type="text" ng-model="song_query" class="form-control" placeholder="Search Query" />
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="song_query && songs.length == 0 && !loading_songs">
              <td>No results for <strong>{{ song_query }}</strong>
            </tr>
            <tr ng-repeat="song in songs">
              <td>
                <song-form song="song" editing="false"></song-form>
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
