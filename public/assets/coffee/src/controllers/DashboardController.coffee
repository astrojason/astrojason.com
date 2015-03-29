window.app.controller 'DashboardController', ['$scope', '$http', '$location', '$timeout', 'UserService', ($scope, $http, $location, $timeout, UserService)->
  $scope.display_category = $location.search().category || ''
  $scope.search_timeout = null
  $scope.daily_links = []
  $scope.selected_links = []
  $scope.search_results = []
  $scope.addingLink = false
  $scope.loading_unread = false

  $scope.$on 'userLoggedIn', ->
    $scope.initDashboard()

  $scope.$on 'userLoggedOut', ->
    $scope.initDashboard()

  $scope.$watch 'display_category', (newValue, oldValue)->
    if newValue != ''
      $scope.getCategoryArticles()

  $scope.$watch 'search_query', (newValue, oldValue)->
    $scope.searching = true
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
    $scope.loading_category = true
    category_Promise = $http.get '/api/links/dashboard/' + $scope.display_category
    category_Promise.success (response)->
      if response.success
        $scope.selected_links = response.links
      else
        $scope.$emit 'errorOccurred', response.error
    category_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading category results'
    category_Promise.finally ->
      $scope.loading_category = false

  $scope.search_articles = ->
    $scope.loading_search = true
    $scope.search_results = []
    data =
      q: $scope.search_query
    if $scope.is_read
      data.include_read = true
    search_Promise = $http.post '/api/links/search', $.param data
    search_Promise.success (response)->
      if response.success
        $scope.searching = false
        $scope.search_results = response.links
      else
        $scope.$emit 'errorOccurred', response.error
    search_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading search results'
    search_Promise.finally ->
      $scope.loading_search = false

  $scope.deleteItem = (link)->
    index = $scope.daily_links.indexOf(link)
    if index >= 0
      $scope.daily_links.splice index, 1
    index = $scope.unread_links.indexOf(link)
    if index >= 0
      $scope.unread_links.splice index, 1
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
    index = $scope.unread_links.indexOf(link)
    if index >= 0
      $scope.unread_links.splice index, 1
    index = $scope.selected_links.indexOf(link)
    if index >= 0
      $scope.selected_links.splice index, 1

  $scope.markAsRead = (link)->
    $scope.total_read++
    index = $scope.daily_links.indexOf(link)
    if index >= 0
      $scope.daily_links.splice index, 1
    index = $scope.unread_links.indexOf(link)
    if index >= 0
      $scope.unread_links.splice index, 1
    index = $scope.selected_links.indexOf(link)
    if index >= 0
      $scope.selected_links.splice index, 1
    index = $scope.unread_links.indexOf(link)
    if index >= 0
      $scope.unread_links.splice index, 1

  $scope.addLink = ->
    $scope.addingLink = true

  $scope.linkAdded = ->
    $scope.addingLink = false
    $scope.newLink = window.Link $scope.$parent.user.id

  $scope.bookAdded = ->
    $scope.addingBook = false

  $scope.initDashboard = ->
    $scope.user = UserService.getUser()
    if $scope.user?.id
      $scope.loadDashboard()

  $scope.loadDashboard = ->
    $scope.newLink = new window.Link($scope.user.id)
    $scope.newBook = new window.Book()
    $scope.newMovie = new window.Movie()
    $scope.newGame = new window.Game()
    $scope.newSong = new window.Song()

    daily_Promise = $http.get '/api/dashboard'
    daily_Promise.success (response)->
      if response.success
        $scope.daily_links = response.links
        $scope.unread_links = response.unread
        $scope.total_read = parseInt response.total_read
        $scope.categories = response.categories
        $scope.total_links = response.total_links
        $scope.links_read = response.links_read
        $scope.total_books = response.total_books
        $scope.books_read = response.books_read
        $scope.books_toread = response.books_toread
        $scope.games_toplay = response.games_toplay
      else
        $scope.$emit 'errorOccurred', response.error
    daily_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading daily results'

  $scope.refreshUnreadArticles = ->
    $scope.loading_unread = true
    category_Promise = $http.get '/api/links/dashboard/unread/20'
    category_Promise.success (response)->
      if response.success
        $scope.unread_links = response.links
      else
        $scope.$emit 'errorOccurred', response.error
    category_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem loading unread results'
    category_Promise.finally ->
      $scope.loading_unread = false

  $scope.populateLinks = ->
    populate_promise = $http.get '/api/links/populate'
    populate_promise.success (response)->
      if response.success
        $scope.loadDashboard()

  $scope.getGameRecommendation = ->
    game_promise = $http.get '/api/games/recommendation'
    game_promise.success (response)->
      alert(response.game.title + ' - ' + response.game.platform)

  $scope.initDashboard()
]
