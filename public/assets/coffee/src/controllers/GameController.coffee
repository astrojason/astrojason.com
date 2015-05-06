window.app.controller 'GameController', ['$scope', 'Game', '$controller', '$timeout', ($scope, Game, $controller, $timeout)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$watch 'recommendingGame', (newValue)->
    if newValue
      $scope.getRecommendation()

  $scope.$watch 'game_query', (newValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_games()
      , 500

  $scope.all = ->
    $scope.loading_games = true
    Game.query (response)->
      $scope.games = response.games
      $scope.loading_games = false

  $scope.search_games = ->
    $scope.searching_games = true
    data =
      q: $scope.game_query
      include_read: $scope.is_read
    Game.query data, (response)->
      $scope.search_results = response.games
      $scope.searching_games = false

  $scope.save = ->
    if $scope.game.category == 'New'
      $scope.game.category = $scope.new_category

    success = ->
      alertify.success "Game " + (if $scope.game.id then "updated" else "added") + " successfully"
      if $scope.game.id
        $scope.editing = false
      else
        $scope.$emit 'closeModal'

    error = ->
      $scope.errorMessage = response.data.error

    game_promise = Game.save $.param $scope.game
    game_promise.$promise.then success, error

  $scope.togglePlayed = ->
    $scope.game.played = !$scope.game.played
    $scope.save()

  $scope.delete = ->
    success = ->
      alertify.success 'Game deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      $scope.game.removed = true

    error = (response)->
      $scope.errorMessage = response.data.error

    game_promise = Game.remove id: $scope.game.id
    game_promise.$promise.then success, error

  $scope.setPlatforms = (platforms)->
    $scope.platforms = platforms

  $scope.checkEditing = ->
    return $scope.game?.id

  $scope.removeGame = (index)->
    $scope.games.splice index, 1

  $scope.getRecommendation = ->
    success = (response)->
      $scope.game = response.game
    error = ->
      console.log 'Something went wrong'

    game_promise = Game.recommend()
    game_promise.$promise.then success, error

]
