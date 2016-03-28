angular.module('astroApp').controller 'FormMasterController', ['$scope', '$location', ($scope, $location)->

    $scope.searching = false
    $scope.updating = false
    $scope.search_timeout = null
    $scope.limit = $location.search().limit || 20
    $scope.page = parseInt $location.search().page || 1
    $scope.descending = false

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
      $scope.nav_pages = []
      if $scope.pages > 1
        if $scope.page >= 5
          start_at = $scope.page - 4
        else
          start_at = 1
        end_at = start_at + 9
        if end_at > $scope.pages
          end_at = $scope.pages

        $scope.nav_pages = [start_at..end_at]
        if $scope.pages > 10 and $scope.nav_pages.length < 10
          $scope.nav_pages = [(end_at - 9)..end_at]

    $scope.toggleSort = (sort)->
      if sort == $scope.sort
        $scope.descending = !$scope.descending
      else
        $scope.sort = sort
        $scope.descending = false
]
