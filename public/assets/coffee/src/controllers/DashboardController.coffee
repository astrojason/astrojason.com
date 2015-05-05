window.app.controller 'DashboardController', ['$scope', '$http', '$location', '$timeout', 'UserService', 'Link', ($scope, $http, $location, $timeout, UserService, Link)->
  $scope.display_category = $location.search().category || ''
  $scope.search_timeout = null
  $scope.daily_links = []
  $scope.selected_links = []
  $scope.search_results = []
  $scope.addingLink = false
  $scope.loading_unread = false
  $scope.recommendingBook = false
  $scope.recommendingGame = false
  $scope.recommendingSong = false
  $scope.addingLink = false
  $scope.addingBook = false
  $scope.addingMovie = false
  $scope.addingGame = false
  $scope.addingSong = false

  $scope.$on 'userLoggedIn', ->
    $scope.initDashboard()

  $scope.$on 'userLoggedOut', ->
    $scope.initDashboard()

  $scope.$on 'linkAdded', ->
    $scope.addingLink = false

  $scope.$on 'bookAdded', ->
    $scope.addingBook = false

  $scope.$on 'movieAdded', ->
    $scope.addingMovie = false

  $scope.$on 'gameAdded', ->
    $scope.addingGame = false

  $scope.$on 'songAdded', ->
    $scope.addingSong = false

  $scope.$watch 'display_category', (newValue)->
    if newValue != ''
      $scope.getCategoryArticles()

  $scope.$watch 'article_search', (newValue)->
    $scope.searching = true
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_articles()
      , 500

  $scope.$watch 'is_read', ->
    $timeout.cancel $scope.search_timeout
    if $scope.article_search?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_articles()
      , 500

  $scope.$watch 'addingLink', ->
    if !$scope.addingLink
      $scope.newLink = new window.Link()

  $scope.$watch 'addingBook', ->
    if !$scope.addingBook
      $scope.newBook = new window.Book()

  $scope.$watch 'addingMovie', ->
    if !$scope.addingMovie
      $scope.newMovie = new window.Movie()

  $scope.$watch 'addingGame', ->
    if !$scope.addingGame
      $scope.newGame = new window.Game()

  $scope.$watch 'addingSong', ->
    if !$scope.addingSong
      $scope.newSong = new window.Song()

  $scope.getCategoryArticles = ->
    $scope.selected_links = []
    $scope.loading_category = true
    data =
      category: $scope.display_category
      limit: 10
      randomize: true
    Link.query data, (response)->
      $scope.selected_links = response.links
      $scope.loading_category = false

  $scope.search_articles = ->
    $scope.searching = true
    $scope.search_results = []

    data =
      q: $scope.article_search
    if $scope.is_read
      data['include_read'] = true
    Link.query data, (response)->
      $scope.search_results = response.links
      $scope.searching = false

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

  $scope.initDashboard = ->
    $scope.user = UserService.getUser()
    if $scope.user?.id
      $scope.loadDashboard()

  $scope.loadDashboard = ->
    daily_Promise = $http.get '/api/dashboard'
    daily_Promise.success (response)->
      if response.success
        $scope.total_read = response.total_read
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

    Link.query category: 'Daily', (response)->
      $scope.daily_links = response.links

    $scope.refreshUnreadArticles()

  $scope.refreshUnreadArticles = ->
    $scope.unread_links = []
    $scope.loading_unread = true
    data =
      category: 'Unread'
      limit: 20
      randomize: true
    Link.query data, (response)->
      $scope.unread_links = response.links
      $scope.loading_unread = false

  $scope.populateLinks = ->
    populate_promise = $http.get '/api/links/populate'
    populate_promise.success (response)->
      if response.success
        $scope.loadDashboard()

  $scope.initDashboard()
]
