window.app.controller 'FormMasterController', ['$scope', ($scope)->

  $scope.searching = false
  $scope.updating = false

  $scope.cancelEdit = ->
    if $scope.updating
      $scope.editing = false
    else
      angular.element($scope.modal).modal 'hide'
      false

  $scope.$watch 'editing', (newValue)->
    if newValue
      if $scope.checkEditing
        $scope.updating = $scope.checkEditing()
]
