window.app.controller 'FormMasterController', ['$scope', '$location', ($scope, $location)->

  $scope.searching = false
  $scope.updating = false
  $scope.search_timeout = null
  $scope.limit = $location.search().limit || 20
  $scope.page = parseInt $location.search().page || 1

  $scope.cancelEdit = ->
    if $scope.updating
      $scope.editing = false
    else
      $scope.$emit 'closeModal'

  $scope.$watch 'editing', (newValue)->
    if newValue
      if $scope.checkEditing
        $scope.updating = $scope.checkEditing()

  $scope.generatePages = ->
    start_at = $scope.page
    end_at = $scope.page + 10
    if end_at > $scope.pages
      end_at = $scope.pages
      start_at = end_at - 10
    $scope.nav_pages = [start_at...end_at]
]
