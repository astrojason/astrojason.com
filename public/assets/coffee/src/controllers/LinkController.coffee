window.app.controller 'LinkController', ['$scope', '$controller', '$filter', '$timeout', '$location', 'Link', ($scope, $controller, $filter, $timeout, $location, Link)->

  $controller 'FormMasterController', $scope: $scope

  $scope.deleting = false
  $scope.errorMessage = false

  $scope.$on 'linkDeleted', (event, message)->
    $scope.links = $filter('filter')($scope.links, {id: '!' + message})
    $scope.link_results = $filter('filter')($scope.link_results, {id: '!' + message})

  $scope.$watch 'link.link', (oldValue, newValue)->
    if oldValue != newValue
      $scope.errorMessage = false

  $scope.$watch 'links_query', (newValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_links()
      , 500

  $scope.$watch 'page', (newValue, oldValue)->
    if newValue != oldValue
      cur_opts = $location.search()
      cur_opts.page = newValue
      $location.search(cur_opts)
      $scope.all()

  $scope.all = ->
    $scope.loading_links = true
    data =
      limit: $scope.limit
      page: $scope.page
    $scope.query data

  $scope.query = (data)->
    Link.query data, (response)->
      $scope.loading_links = false
      $scope.links = response.links
      $scope.total = response.total
      $scope.pages = response.pages
      $scope.generatePages()


  $scope.search_links = ->
    $scope.searching_links = true
    data =
      q: $scope.links_query
    Link.query data, (response)->
      $scope.link_results = response.links
      $scope.searching_links = false

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
