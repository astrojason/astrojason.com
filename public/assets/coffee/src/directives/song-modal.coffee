angular.module('astroApp').directive 'songModal', ->
  templateUrl: 'templates/song-modal'
  restrict: 'E'
  controller: 'SongController'
  replace: true