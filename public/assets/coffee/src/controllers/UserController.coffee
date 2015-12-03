angular.module('astroApp').controller 'UserController', ['$scope', '$http', ($scope, $http)->

    $scope.$on 'checkingAvailability', ->
      $scope.submitting = true

    $scope.$on 'checkedAvailability', ->
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
      registration_Promise.success ->
        $scope.registrationSuccess = true
      registration_Promise.error ->
        $scope.$emit 'errorOccurred', 'Problem with registration'
]