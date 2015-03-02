window.app.directive 'movieForm', ->
  templateUrl: 'templates/movie-form'
  restrict: 'E',
  controller: 'MovieController',
  scope: {
    movie: '=',
    editing: '='
  }
