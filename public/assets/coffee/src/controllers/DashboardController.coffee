window.app.controller 'DashboardController', ['$scope', '$http', '$location', '$timeout', 'UserService', ($scope, $http, $location, $timeout, UserService)->
  $scope.display_category = $location.search().category || ''
  $scope.search_timeout = null
  $scope.daily_links = []
  $scope.selected_links = []
  $scope.search_results = []
  $scope.addingLink = false

  $scope.$emit 'initStarted'

  $scope.$watch 'display_category', (newValue, oldValue)->
    if newValue != ''
      $scope.getCategoryArticles()

  $scope.$watch 'search_query', (newValue, oldValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_articles()
      , 500

  $scope.$watch 'is_read', ->
    $timeout.cancel $scope.search_timeout
    if $scope.search_query?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_articles()
      , 500

  $scope.getCategoryArticles = ->
    $scope.$emit 'initStarted'
    category_Promise = $http.get '/api/links/dashboard/' + $scope.display_category
    category_Promise.success (response)->
      if response.success
        $scope.$emit 'initComplete'
        $scope.selected_links = response.links
      else
        $scope.$emit 'errorOccurred', response.error
    category_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading category results'

  $scope.search_articles = ->
    $scope.$emit 'initStarted'
    $scope.search_results = []
    data =
      q: $scope.search_query
    if $scope.is_read
      data.include_read = true
    search_Promise = $http.post '/api/links/search', $.param data
    search_Promise.success (response)->
      $scope.$emit 'initComplete'
      if response.success
        $scope.search_results = response.links
      else
        $scope.$emit 'errorOccurred', response.error
    search_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading search results'

  $scope.deleteItem = (link)->
    index = $scope.daily_links.indexOf(link)
    if index >= 0
      $scope.daily_links.splice index, 1
    index = $scope.selected_links.indexOf(link)
    if index >= 0
      $scope.selected_links.splice index, 1
    index = $scope.search_results.indexOf(link)
    if index >= 0
      $scope.search_results.splice index, 1

  $scope.changeCategory = (link)->
    index = $scope.daily_links.indexOf(link)
    if index >= 0
      $scope.daily_links.splice index, 1
    index = $scope.selected_links.indexOf(link)
    if index >= 0
      $scope.selected_links.splice index, 1

  $scope.markAsRead = (link)->
    $scope.total_read++
    index = $scope.daily_links.indexOf(link)
    if index >= 0
      $scope.daily_links.splice index, 1
    index = $scope.selected_links.indexOf(link)
    if index >= 0
      $scope.selected_links.splice index, 1

  $scope.addLink = ->
    $scope.addingLink = true

  $scope.linkAdded = ->
    $scope.addingLink = false
    $scope.newLink = window.Link($scope.$parent.user.id)

  $scope.initDashboard = ->
    console.log UserService.getUser()
#      $scope.newLink = window.Link($scope.$parent.user.id)
#      daily_Promise = $http.get '/api/links/dashboard'
#      daily_Promise.success (response)->
#        if response.success
#          $scope.$emit 'initComplete'
#          $scope.daily_links = response.links
#          $scope.unread_links = response.unread
#          $scope.total_read = parseInt response.total_read
#        else
#          $scope.$emit 'errorOccurred', response.error
#      daily_Promise.error ->
#        $scope.$emit 'errorOccurred', 'Problem loading daily results'

  $scope.initDashboard()
]