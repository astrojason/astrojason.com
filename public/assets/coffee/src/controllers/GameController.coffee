window.app.controller 'GameController', ['$scope', '$filter', '$controller', '$timeout', '$location', 'Game', ($scope, $filter, $controller, $timeout, $location, Game)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'gameDeleted', (event, message)->
    $scope.games = $filter('filter')($scope.games, {id: '!' + message})
    $scope.game_results = $filter('filter')($scope.game_results, {id: '!' + message})

  $scope.$watch '', (newValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_games()
      , 500

  $scope.triggerRecommender = ->
    $scope.$watch 'recommendingGame', (newValue)->
      if newValue
        $scope.getRecommendation()

  $scope.initList = ->

    $scope.query()

    $scope.$watch 'game_query', ->
      $timeout.cancel $scope.search_timeout
      if !$scope.loading_games
        $scope.search_timeout = $timeout ->
          $scope.query()
        , 500

    $scope.$watch 'page', (newValue, oldValue)->
      if !$scope.loading_games
        if newValue != oldValue
          cur_opts = $location.search()
          cur_opts.page = newValue
          $location.search(cur_opts)
          $scope.query()

    $scope.$watch 'display_category', ->
      if !$scope.loading_games
        $scope.query()

  $scope.query = ->
    $scope.loading_games = true
    data = []
    if $scope.game_query
      data['q'] = $scope.game_query
    Game.query data, (response)->
      $scope.games = response.games
      $scope.loading_games = false


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
      $scope.$emit 'gameDeleted', $scope.game.id

    error = (response)->
      $scope.errorMessage = response.data.error

    game_promise = Game.remove id: $scope.game.id
    game_promise.$promise.then success, error

  $scope.setPlatforms = (platforms)->
    $scope.platforms = platforms

  $scope.checkEditing = ->
    return $scope.game?.id

  $scope.getRecommendation = ->
    success = (response)->
      $scope.game = response.game
    error = ->
      console.log 'Something went wrong'

    game_promise = Game.recommend()
    game_promise.$promise.then success, error

]
