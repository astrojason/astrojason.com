angular.module('astroApp').controller 'UserController', ['$scope', 'UserResource', ($scope, UserResource)->

  $scope.submitting = false
  $scope.registrationSuccess = false

  $scope.$on 'checkingAvailability', ->
    $scope.submitting = true

  $scope.$on 'checkedAvailability', ->
    $scope.submitting = false

  $scope.registerUser = ->
    $scope.submitting = true

    data =
      first_name: $scope.first_name
      last_name: $scope.last_name
      email: $scope.email
      username: $scope.username
      password: $scope.password

    registration_Promise = UserResource.add(data).$promise

    registration_Promise.then ->
      $scope.registrationSuccess = true

    registration_Promise.catch ->
      $scope.$emit 'errorOccurred', 'Problem with registration'

    registration_Promise.finally ->
      $scope.submitting = false
]