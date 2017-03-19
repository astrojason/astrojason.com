angular.module('astroApp').directive 'movieForm', ->
  templateUrl: 'templates/movie-form'
  restrict: 'E'
  controller: 'MovieController'
  scope:
    movie: '='