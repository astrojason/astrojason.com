@extends('v1.layouts.layout')

@section('title')
  Songs
@stop

@section('content')
  <div ng-controller="SongController" ng-init="setArtists(<% $song_artists %>)">
    <div class="row" ng-show="songs.length == 0 && !loading_songs && !song_query" ng-cloak>
      <div class="col-xs-12">
        <div class="alert alert-info text-center">
          You do not have any songs, would you like me to
          <button class="btn btn-default" ng-click="populateSongs()">
            Randomize
          </button>
          some for you?
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_songs" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
            <tr>
              <th>Songs<button class="btn btn-success btn-xs pull-right" ng-click="songModalOpen = true">Add</button></th>
            </tr>
            <tr>
              <th class="input-group">
                <div class="search-wrapper">
                  <input type="text" ng-model="song_query" class="form-control" placeholder="Search Query" />
                  <small
                    class="clear-input glyphicon glyphicon-remove-circle"
                    ng-show="song_query"
                    ng-click="song_query = ''"></small>
                </div>
                <div class="input-group-addon"><input type="checkbox" ng-model="include_learned" /> <label>Include learned</label></div>
              </th>
            </tr>
            <tr>
              <th class="input-group">
                <div class="input-group-addon">Artist</div>
                <select name="artist" ng-model="display_artist" ng-options="artist for artist in artists" class="form-control">
                  <option value="">All</option>
                </select>
              </th>
            </tr>
            <tr>
              <th>
                <div class="col-md-4">
                  <a href="#" ng-click="toggleSort('title')">Title</a>
                </div>
                <div class="col-md-4">
                  <a href="#" ng-click="toggleSort('artist')">Artist</a>
                </div>
                <div class="col-md-4">
                  <a href="#" ng-click="toggleSort('times_recommended')">Times Recommended</a>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="song_query && songs.length == 0 && !loading_songs">
              <td>No results for <strong>{{ song_query }}</strong>
            </tr>
            <tr ng-repeat="song in songs" ng-class="{read: song.learned}">
              <td ng-class="{new: song.new}">
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
    <song-modal></song-modal>
  </div>
@stop
