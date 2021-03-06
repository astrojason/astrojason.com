angular.module('astroApp').controller 'UserController', [
  '$scope'
  '$log'
  'UserResource'
  ($scope,
    $log,
    UserResource)->

    $scope.submitting = false
    $scope.registrationSuccess = false

    $scope.$on 'checkingAvailability', ->
      $scope.submitting = true

    $scope.$on 'checkedAvailability', ->
      $scope.submitting = false

    $scope.registerUser = ->
      $scope.submitting = true

      registration_Promise = UserResource.add($scope.user).$promise

      registration_Promise.then ->
        $log.debug 'Registration was successful'
        $scope.registrationSuccess = true

      registration_Promise.catch ->
        $scope.$emit 'errorOccurred', 'Problem with registration'

      registration_Promise.finally ->
        $scope.submitting = false
]