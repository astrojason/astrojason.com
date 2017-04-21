angular.module('astroApp').controller 'DashboardController', [
  '$scope'
  '$location'
  '$timeout'
  '$filter'
  '$log'
  'UserService'
  'DashboardResource'
  'LinkResource'
  'ArticleResource'
  'Link'
  'Book'
  'Game'
  'Song'
  ($scope,
    $location,
    $timeout,
    $filter,
    $log,
    UserService,
    DashboardResource,
    LinkResource,
    ArticleResource,
    Link,
    Book,
    Game,
    Song)->

    $scope.display_category = $location.search().category || ''
    $scope.search_timeout = null
    $scope.article_results = []
    $scope.loading_unread = false
    $scope.loading_category = false
    $scope.recommendingBook = false
    $scope.recommendingGame = false
    $scope.recommendingSong = false
    $scope.linkModalOpen = false
    $scope.bookModalOpen = false
    $scope.gameModalOpen = false
    $scope.songModalOpen = false

    $scope.$on 'userLoggedIn', ->
      $scope.initDashboard()

    $scope.$on 'userLoggedOut', ->
      $scope.initDashboard()

    $scope.$on 'linkDeleted', (event, linkId)->
      $scope.links_list = $filter('filter')($scope.links_list, id: "!#{linkId}")
      $scope.selected_articles = $filter('filter')($scope.selected_articles, id: "!#{linkId}")
      $scope.article_results = $filter('filter')($scope.article_results, id: "!#{linkId}")

    $scope.$on 'linkUpdated', ->
      links_list_filter =
        category: $scope.viewing_category
        is_read: false
      $scope.links_list = $filter('filter')($scope.links_list, links_list_filter)
      category_filter =
        category: $scope.display_category
        is_read: false
      $scope.selected_articles = $filter('filter')($scope.selected_articles, category_filter)
      if !$scope.is_read
        $scope.article_results = $filter('filter')($scope.article_results, is_read: false)

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
      $scope.gameModalOpen = false
      $scope.songModalOpen = false

    $scope.$watch 'display_category', (newValue)->
      if newValue != ''
        $scope.getArticlesForCategory newValue, 10, true, false, true

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

    $scope.$watch 'gameModalOpen', ->
      if !$scope.gameModalOpen
        $scope.newGame = new Game()

    $scope.$watch 'songModalOpen', ->
      if !$scope.songModalOpen
        $scope.newSong = new Song()

    $scope.getListName = (category)->
      "#{category.toLowerCase().replace(' ', '_')}"

    $scope.getArticlesForCategory = (category, limit, randomize, update_load_count, isCategoryList = false)->
      if isCategoryList
        $scope.loading_category = true
        $scope.selected_articles = []
      else
        $scope.loading_links = true
        $scope.links_list = []
      data =
        category: category.id

      if randomize
        data.randomize = true

      if update_load_count
        data.update_load_count = true

      if limit
        data.limit = limit

      categoryArticlesPromise = ArticleResource.query(data).$promise
      categoryArticlesPromise.then (articles)->
        if isCategoryList
          $scope.selected_articles = articles
        else
          $scope.links_list = articles

      categoryArticlesPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not load links for category'

      categoryArticlesPromise.finally ->
        $scope.loading_links = false
        $scope.loading_category = false

    $scope.search_articles = ->
      $scope.searching = true
      $scope.article_results = []

      data =
        q: $scope.article_search
      if $scope.is_read
        data['include_read'] = true

      searchPromise = ArticleResource.query(data).$promise

      searchPromise.then (articles)->
        $scope.article_results = articles

      searchPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not get perform the search'

      searchPromise.finally ->
        $scope.searching = false

    $scope.initDashboard = ->
      $scope.user = UserService.get()
      if $scope.user?.id
        $scope.loadDashboard()

    $scope.loadDashboard = ->
      articles_promise = ArticleResource.daily().$promise

      articles_promise.then (articles)->
        $scope.daily_articles = articles.filter (article)->
          !article.readToday() && !article.postponedToday()

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
        $scope.display_categories = response.dashboard_layout
      daily_Promise.catch ->
        $scope.$emit 'errorOccurred', 'Problem loading daily results'

    $scope.populateLinks = ->
      populate_promise = LinkResource.populate().$promise
      populate_promise.then ->
        $scope.loadDashboard()

    $scope.refreshReadCount = ->
      readCount_promise = LinkResource.readToday().$promise

      readCount_promise.then (response)->
        $scope.total_read = response.total_read

    $scope.removeArticleFromList = (article, list)->
      $scope[list] = $scope[list].filter (list_article)->
        list_article.id != article.id

    $scope.readArticle = (article, list)->
      article.markRead().then ->
        $scope.removeArticleFromList(article, list)

    $scope.postponeArticle = (article)->
      article.postpone().then ->
        $scope.removeArticleFromList(article, 'daily_articles')

    $scope.filterDeletedArticles = (list)->
      if $scope[list]
        deleted_articles = $scope[list].filter (article)->
          article.deleted
        if deleted_articles.length > 0
          deleted_articles.forEach (article)->
            $scope.removeArticleFromList(article, list)

    $scope.$watch 'daily_articles', ->
      $scope.filterDeletedArticles('daily_articles')
    , true

    $scope.$watch 'selected_articles', ->
      $scope.filterDeletedArticles('selected_articles')
    , true

    $scope.$watch 'article_results', ->
      $scope.filterDeletedArticles('article_results')
    , true
]