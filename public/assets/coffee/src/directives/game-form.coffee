window.app.directive 'gameForm', ->
  templateUrl: 'templates/game-form'
  restrict: 'E'
  controller: 'GameController'
  scope:
    game: '='
    editing: '='
