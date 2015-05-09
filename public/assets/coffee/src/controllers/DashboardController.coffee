window.app.controller 'DashboardController', ['$scope', '$http', '$location', '$timeout', '$filter', 'UserService', 'Link', ($scope, $http, $location, $timeout, $filter, UserService, Link)->
  $scope.display_category = $location.search().category || ''
  $scope.search_timeout = null
  $scope.daily_links = []
  $scope.selected_links = []
  $scope.link_results = []
  $scope.loading_unread = false
  $scope.recommendingBook = false
  $scope.recommendingGame = false
  $scope.recommendingSong = false
  $scope.linkModalOpen = false
  $scope.bookModalOpen = false
  $scope.movieModalOpen = false
  $scope.gameModalOpen = false
  $scope.songModalOpen = false

  $scope.$on 'userLoggedIn', ->
    $scope.initDashboard()

  $scope.$on 'userLoggedOut', ->
    $scope.initDashboard()

  $scope.$on 'linkDeleted', (event, message)->
    $scope.daily_links = $filter('filter')($scope.daily_links, {id: '!' + message})
    $scope.unread_links = $filter('filter')($scope.unread_links, {id: '!' + message})
    $scope.selected_links = $filter('filter')($scope.selected_links, {id: '!' + message})
    $scope.link_results = $filter('filter')($scope.link_results, {id: '!' + message})

  $scope.$on 'linkUpdated', (event, message)->
    $scope.daily_links = $filter('filter')($scope.daily_links, {category: 'Daily'})
    $scope.unread_links = $filter('filter')($scope.unread_links, {category: 'Unread'})
    $scope.selected_links = $filter('filter')($scope.selected_links, {category: $scope.display_category})

  $scope.$on 'closeModal', ->
    $scope.linkModalOpen = false
    $scope.bookModalOpen = false
    $scope.movieModalOpen = false
    $scope.gameModalOpen = false
    $scope.songModalOpen = false

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
    if !$scope.linkModalOpen
      $scope.newLink = new window.Link()

  $scope.$watch 'bookModalOpen', ->
    if !$scope.bookModalOpen
      $scope.newBook = new window.Book()

  $scope.$watch 'movieModalOpen', ->
    if !$scope.movieModalOpen
      $scope.newMovie = new window.Movie()

  $scope.$watch 'gameModalOpen', ->
    if !$scope.gameModalOpen
      $scope.newGame = new window.Game()

  $scope.$watch 'songModalOpen', ->
    if !$scope.songModalOpen
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
    $scope.link_results = []

    data =
      q: $scope.article_search
    if $scope.is_read
      data['include_read'] = true
    Link.query data, (response)->
      $scope.link_results = response.links
      $scope.searching = false

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
        $scope.songs_toplay = response.songs_toplay
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
