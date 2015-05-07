@extends('layouts.layout')

@section('title')
  Songs
@stop

@section('content')
  <div ng-controller="SongController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="searching_songs" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover">
          <thead>
          <tr>
            <th>
              <input type="text" ng-model="song_query" class="form-control" placeholder="Search Query" />
            </th>
          </tr>
          </thead>
          <tbody>
          <tr ng-show="song_query && song_results.length == 0 && !searching_songs">
            <td>No results for <strong>{{ song_query }}</strong>
          </tr>
          <tr ng-repeat="song in song_results" ng-show="song_results.length > 0" ng-cloak>
            <td><song-form song="song" editing="false"></song-form></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_songs" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="all()">
          <tbody>
          <tr ng-repeat="song in songs">
            <td>
              <song-form song="song" editing="false"></song-form>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop
