window.app.controller 'LinkController', ['$scope', '$controller', '$filter', '$timeout', '$location', '$http', 'Link', ($scope, $controller, $filter, $timeout, $location, $http, Link)->

  $controller 'FormMasterController', $scope: $scope

  $scope.deleting = false
  $scope.errorMessage = false
  $scope.originalLink = angular.copy $scope.link
  $scope.importedCount = 0

  $scope.$on 'linkDeleted', (event, message)->
    $scope.links = $filter('filter')($scope.links, {id: '!' + message})
    $scope.link_results = $filter('filter')($scope.link_results, {id: '!' + message})

  $scope.$watch 'link.link', (oldValue, newValue)->
    if oldValue != newValue
      $scope.errorMessage = false

  $scope.initList = ->

    $scope.newLink = new window.Link()

    $scope.query()

    $scope.$on 'closeModal', (event, link)->
      $scope.linkModalOpen = false
      if link
        link.new = true
        $scope.links.splice(0, 0, link)
        $timeout ->
          link.new = false
        , 1000

    $scope.$watch 'links_query', ->
      if !$scope.loading_links
        $timeout.cancel $scope.search_timeout
        if !$scope.loading_links
          $scope.search_timeout = $timeout ->
            $scope.query()
          , 500

    $scope.$watch 'page', (newValue, oldValue)->
      if !$scope.loading_links
        if newValue != oldValue
          cur_opts = $location.search()
          cur_opts.page = newValue
          $location.search(cur_opts)
          $scope.query()

    $scope.$watch 'display_category', ->
      if !$scope.loading_links
        $scope.query()

  $scope.query = ->
    $scope.loading_links = true
    data =
      limit: $scope.limit
      page: $scope.page
    if $scope.links_query
      data['q'] = $scope.links_query
    if $scope.display_category
      data['category'] = $scope.display_category
    Link.query data, (response)->
      $scope.loading_links = false
      $scope.links = response.links
      $scope.total = response.total
      $scope.pages = response.pages
      $scope.generatePages()

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

    success = (response)->
      alertify.success "Link " + (if $scope.link.id then "updated" else "added") + " successfully"
      if $scope.link.id
        $scope.editing = false
        $scope.$emit 'linkUpdated', $scope.link
        if $scope.originalLink.is_read != $scope.link.is_read
          $scope.$emit 'linkRead', $scope.link.is_read
      else
        $scope.$emit 'closeModal', response.link

    error = (response)->
      $scope.errorMessage = response.data.error

    link_promise = Link.save $.param $scope.link
    link_promise.$promise.then success, error

  $scope.setCategories = (categories)->
    $scope.categories = categories

  $scope.importLinks = ->
    $scope.importedCount = 0
    submitLinks = []
    links = $scope.importlist.split 'http'
    angular.forEach links, (link)->
      if link != ''
        exploded = link.split '|'
        thisLink =
          url: ('http' + exploded[0]).trim()
          name: exploded[1].trim()
        submitLinks.push thisLink
    data =
      importlist: submitLinks

    importPromise = $http.post '/api/link/import', $.param data
    importPromise.success (response)->
      $scope.importedCount = response.count
    importPromise.error ->
      console.log 'This is bad'
]
