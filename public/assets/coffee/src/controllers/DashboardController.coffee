angular.module('astroApp').controller 'DashboardController', ['$scope', '$http', '$location', '$timeout', '$filter',
  'UserService', 'DashboardResource', 'LinkResource', 'Link', 'Book', 'Movie', 'Game', 'Song', ($scope, $http, $location, $timeout,
  $filter, UserService, DashboardResource, LinkResource, Link, Book, Movie, Game, Song)->

    $scope.display_category = $location.search().category || ''
    $scope.search_timeout = null
    $scope.daily_links = []
    $scope.selected_links = []
    $scope.link_results = []
    $scope.loading_unread = false
    $scope.loading_category = false
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

    $scope.$on 'linkUpdated', ->
      daily_links =
        category: 'Daily'
        is_read: false
      unread_links =
        category: 'Unread'
        is_read: false
      selected_links =
        category: $scope.display_category
        is_read: false
      $scope.daily_links = $filter('filter')($scope.daily_links, daily_links)
      $scope.unread_links = $filter('filter')($scope.unread_links, unread_links)
      $scope.selected_links = $filter('filter')($scope.selected_links, selected_links)

    $scope.$on 'linkRead', (event, message)->
      if message
        $scope.links_read += 1
        $scope.total_read += 1
      else
        $scope.links_read -= 1
        $scope.total_read -= 1

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

    $scope.$watch 'linkModalOpen', ->
      if !$scope.linkModalOpen
        $scope.newLink = new Link()

    $scope.$watch 'bookModalOpen', ->
      if !$scope.bookModalOpen
        $scope.newBook = new Book()

    $scope.$watch 'movieModalOpen', ->
      if !$scope.movieModalOpen
        $scope.newMovie = new Movie()

    $scope.$watch 'gameModalOpen', ->
      if !$scope.gameModalOpen
        $scope.newGame = new Game()

    $scope.$watch 'songModalOpen', ->
      if !$scope.songModalOpen
        $scope.newSong = new Song()

    $scope.getCategoryArticles = ->
      $scope.selected_links = []
      $scope.loading_category = true
      data =
        category: $scope.display_category
        limit: 10
        randomize: true
        update_load_count: true

      categoryLinksPromise = LinkResource.query(data).$promise
      categoryLinksPromise.then (links)->
        $scope.selected_links = links

      categoryLinksPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not load links for category'

      categoryLinksPromise.finally ->
        $scope.loading_category = false

    $scope.search_articles = ->
      $scope.searching = true
      $scope.link_results = []

      data =
        q: $scope.article_search
      if $scope.is_read
        data['include_read'] = true

      searchPromise = LinkResource.query(data).$promise

      searchPromise.then (links)->
        $scope.link_results = links

      searchPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not get perform the search'

      searchPromise.finally ->
        $scope.searching = false

    $scope.initDashboard = ->
      $scope.user = UserService.get()
      if $scope.user?.id
        $scope.loadDashboard()

    $scope.loadDashboard = ->
      daily_Promise = DashboardResource.get().$promise
      daily_Promise.then (response)->
        $scope.total_read = response.total_read
        $scope.categories = response.categories
        $scope.total_links = response.total_links
        $scope.links_read = response.links_read
        $scope.total_books = response.total_books
        $scope.books_read = response.books_read
        $scope.books_toread = response.books_toread
        $scope.games_toplay = response.games_toplay
        $scope.songs_toplay = response.songs_toplay
      daily_Promise.catch ->
        $scope.$emit 'errorOccurred', 'Problem loading daily results'

      LinkResource.query category: 'Daily', (links)->
        $scope.daily_links = links

      $scope.refreshUnreadArticles()

#   TODO: This is a duplicate call, should be able to combine it with getCategoryArticles
    $scope.refreshUnreadArticles = ->
      $scope.unread_links = []
      $scope.loading_unread = true
      data =
        category: 'Unread'
        limit: 20
        randomize: true
        update_load_count: true
      unreadPromise = LinkResource.query(data).$promise

      unreadPromise.then (links)->
        $scope.unread_links = links

      unreadPromise.finally ->
        $scope.loading_unread = false

#   TODO: Put this into the LinkResource service
    $scope.populateLinks = ->
      populate_promise = $http.get '/api/links/populate'
      populate_promise.success ->
        $scope.loadDashboard()

    $scope.refreshReadCount = ->
      readCount_promise = LinkResource.readToday().$promise

      readCount_promise.then (response)->
        $scope.total_read = response.total_read
]
