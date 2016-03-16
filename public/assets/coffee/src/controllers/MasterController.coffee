angular.module('astroApp').controller 'MasterController', ['$scope', 'UserService', 'UserResource'
  ($scope, UserService, UserResource) ->
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

      loginPromise = UserResource.login(data).$promise

      loginPromise.then (user)->
        $scope.initUser user
        $scope.$broadcast 'userLoggedIn'

      loginPromise.catch ->
        $scope.show_error = true
        $scope.error_message = 'Could not log you in.'

    $scope.logout = ->
      logoutPromise = UserResource.logout().$promise
      logoutPromise.then ->
        $scope.initUser null
        $scope.$broadcast 'userLoggedOut'

    $scope.initUser = (user)->
      $scope.user = user
      UserService.set $scope.user
]