window.app.controller 'GameController', ['$scope', '$http', '$controller', '$timeout', ($scope, $http, $controller, $timeout)->

  $scope.search_timeout = null

  $controller 'FormMasterController', $scope: $scope

  $scope.setPlatforms = (platforms)->
    $scope.platforms = platforms

  $scope.save = ->
    data = $scope.game
    if $scope.game.category == 'New'
      $scope.game.category = $scope.new_category
    game_promise = $http.post '/api/games/save', $.param data
    game_promise.success (response)->
      if response.success
        alertify.success "Game " + (if 0 == parseInt $scope.game.id then "added" else "updated") + " successfully"
      else
        $scope.errorMessage = response.error
        if $scope.$parent.saveError
          $scope.$parent.saveError response.error
    game_promise.error ->
      $scope.$emit 'errorOccurred', 'Problem ' + ($scope.game.id ? 'updating' : 'adding') + ' game.'

  $scope.$watch 'search_query', (newValue, oldValue)->
    $scope.searching = true
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_games()
      , 500

  $scope.delete = ->
    read_Promise = $http.post '/api/games/delete/' + $scope.game.id
    read_Promise.success (response)->
      if response.success
        if $scope.$parent.deleteItem
          $scope.$parent.deleteItem $scope.game

  $scope.deleteItem = (game)->
    $scope.search_results.splice($scope.search_results.indexOf(game), 1)

  $scope.search_games = ->
    data =
      q: $scope.search_query
      include_read: $scope.is_read
    search_promise = $http.post '/api/games/search', $.param data
    search_promise.success (response)->
      $scope.search_results = response.games

  $scope.cancelEdit = ->
    if $scope.game.id
      $scope.editing = false
    else
      angular.element('#addGameModal').modal('hide')
      false

  $scope.getGameRecommendation = ->
    game_promise = $http.get '/api/games/recommendation'
    game_promise.success (response)->
      $scope.game = response.game
]
