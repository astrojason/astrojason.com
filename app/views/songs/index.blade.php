@extends('layouts.layout')

@section('title')
  Songs
@stop

@section('content')
  <div ng-controller="SongController">
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
      <tr ng-repeat="movie in search_results" ng-show="search_results.length > 0" ng-cloak>
        <td><song-form song="song" editing="false"></song-form></td>
      </tr>
      </tbody>
    </table>
    <table class="table table-condensed table-striped table-hover" ng-init="all()">
      <tbody>
      <tr ng-repeat="song in songs track by $index">
        <td>
          <song-form song="song" editing="false" index="$index"></song-form>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
@stop
