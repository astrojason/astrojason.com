target = document.getElementById('register_overlay');
spinner = new Spinner(spin_opts).spin(target)

window.app.controller 'UserController', ['$scope', '$http', ($scope, $http)->

  $scope.$on 'checkingAvailibility', ->
    $scope.submitting = true

  $scope.$on 'checkedAvailibility', ->
    $scope.submitting = false

  $scope.registerUser = ->
    data =
      first_name: $scope.first_name
      last_name: $scope.last_name
      email: $scope.email
      username: $scope.username
      password: $scope.password
    $scope.submitting = true
    registration_Promise = $http.post '/api/register', $.param data
    registration_Promise.success (data)->
      console.log data
    registration_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem with registration'
]