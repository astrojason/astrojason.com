window.app.controller 'LinkController', ['$scope', '$controller', '$filter', 'Link', ($scope, $controller, $filter, Link)->

  $controller 'FormMasterController', $scope: $scope

  $scope.deleting = false
  $scope.errorMessage = false

  $scope.$on 'linkDeleted', (event, message)->
    $scope.links = $filter('filter')($scope.links, {id: '!' + message})
    $scope.link_results = $filter('filter')($scope.link_results, {id: '!' + message})

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
      $scope.$emit 'linkDeleted', $scope.link.id

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
        $scope.$emit 'linkUpdated', $scope.link
      else
        $scope.$emit 'closeModal'

    error = (response)->
      $scope.errorMessage = response.error

    link_promise = Link.save $.param $scope.link
    link_promise.$promise.then success, error

  $scope.setCategories = (categories)->
    $scope.categories = categories
]
