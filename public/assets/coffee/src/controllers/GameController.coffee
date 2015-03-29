window.app.controller 'GameController', ['$scope', '$http', '$controller', ($scope, $http, $controller)->

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
]
