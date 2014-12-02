window.app.controller 'MasterController', ['$scope', '$http', ($scope, $http) ->
  $scope.initItems = 0

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
    $scope.init = true
    data =
      username: $scope.username
      password: $scope.password
    login_Promise = $http.post '/api/login', $.param data
    login_Promise.success((data)->
      $scope.init = false
      $scope.user = data.user
    )

  $scope.logout = ->
    $scope.init = true
    login_Promise = $http.post '/api/logout'
    login_Promise.success(->
      $scope.init = false
      $scope.user = null
    )

]
