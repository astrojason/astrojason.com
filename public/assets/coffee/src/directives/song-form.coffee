angular.module('astroApp').directive 'songForm', ->
  templateUrl: 'templates/song-form'
  restrict: 'E'
  controller: 'SongController'
  scope:
    song: '='
    editing: '='