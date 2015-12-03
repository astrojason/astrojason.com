angular.module('astroApp').directive 'movieModal', ->
  templateUrl: 'templates/movie-modal'
  restrict: 'E'
  controller: 'MovieController'
  replace: true