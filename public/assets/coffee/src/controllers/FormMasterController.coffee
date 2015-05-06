window.app.controller 'FormMasterController', ['$scope', ($scope)->

  $scope.searching = false
  $scope.updating = false
  $scope.search_timeout = null

  $scope.cancelEdit = ->
    if $scope.updating
      $scope.editing = false
    else
      $scope.$emit 'closeModal'

  $scope.$watch 'editing', (newValue)->
    if newValue
      if $scope.checkEditing
        $scope.updating = $scope.checkEditing()
]
