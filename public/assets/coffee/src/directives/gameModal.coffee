angular.module('astroApp').directive 'gameModal', ->
  templateUrl: 'templates/game-modal'
  restrict: 'E'
  controller: 'GameController'
  replace: true