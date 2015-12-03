angular.module('astroApp').directive 'gameForm', ->
  templateUrl: 'templates/game-form'
  restrict: 'E'
  controller: 'GameController'
  scope:
    game: '='
    editing: '='
