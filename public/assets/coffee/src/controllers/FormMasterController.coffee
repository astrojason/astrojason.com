window.app.controller 'FormMasterController', ['$scope', ($scope)->

  $scope.cancelEdit = ->
    if $scope.id
      $scope.editing = false
]
