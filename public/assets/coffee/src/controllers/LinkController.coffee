window.app.controller 'LinkController', ['$scope', '$controller', 'Link', ($scope, $controller, Link)->

  $controller 'FormMasterController', $scope: $scope

  $scope.deleting = false
  $scope.errorMessage = false

  $scope.$watch 'link.link', (oldValue, newValue)->
    if oldValue != newValue
      $scope.errorMessage = false

  $scope.linkOpened = ->
    $scope.link.times_read += 1
    $scope.save()

  $scope.toggleRead = ->
    $scope.link.is_read = !$scope.link.is_read
    $scope.save()

  $scope.delete = ->
    success = ->
      alertify.success 'Link deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      if $scope.$parent.removeLink
        $scope.$parent.removeLink $scope.index

    error = (response)->
      $scope.errorMessage = response.data.error

    link_promise = Link.remove id: $scope.link.id
    link_promise.$promise.then success, error

  $scope.save = ->
    if $scope.link.category == 'New'
      $scope.link.category = $scope.new_category

    success = ->
      alertify.success "Link " + (if $scope.link.id then "updated" else "added") + " successfully"
      if $scope.link.id
        $scope.editing = false
      else
        $scope.$emit 'linkAdded'

    error = (response)->
      $scope.errorMessage = response.error

    link_promise = Link.save $.param $scope.link
    link_promise.$promise.then success, error

  $scope.setCategories = (categories)->
    $scope.categories = categories
]
