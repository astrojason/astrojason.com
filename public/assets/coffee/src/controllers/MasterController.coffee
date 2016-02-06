angular.module('astroApp').controller 'MasterController', ['$scope', 'UserService',
  ($scope, UserService) ->
    $scope.init = false
    $scope.initItems = 0
    $scope.show_error = false
    $scope.error_message = ''

    $scope.$on 'initStarted', ->
      $scope.init = true
      $scope.initItems++

    $scope.$on 'initComplete', ->
      $scope.initItems--
      if $scope.initItems == 0
        $scope.init = false

    $scope.$on 'errorOccurred', (event, data)->
      $scope.show_error = true
      $scope.error_message = data

    $scope.login = ->
      data =
        username: $scope.username
        password: $scope.password

      UserService.login data

    $scope.logout = ->
      UserService.logout()

    $scope.initUser = (user)->
      $scope.user = user
      UserService.set $scope.user
]