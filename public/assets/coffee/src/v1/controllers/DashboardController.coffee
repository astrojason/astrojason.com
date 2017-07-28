angular.module('astroApp').controller 'DashboardController', [
  '$scope'
  '$location'
  '$filter'
  '$log'
  'UserService'
  'DashboardResource'
  'ArticleResource'
  'Book'
  'Game'
  'Song'
  ($scope,
    $location,
    $filter,
    $log,
    UserService,
    DashboardResource,
    ArticleResource,
    Book,
    Game,
    Song)->

    $scope.display_category = $location.search().category || ''
    $scope.search_timeout = null
    $scope.article_results = []
    $scope.loading_unread = false
    $scope.loading_category = false
    $scope.loading_tasks = false
    $scope.updating_status = false
    $scope.recommendingBook = false
    $scope.recommendingGame = false
    $scope.recommendingSong = false
    $scope.bookModalOpen = false
    $scope.gameModalOpen = false
    $scope.songModalOpen = false
    $scope.newArticle = new ArticleResource()

    $scope.$on 'article_read', (event, message)->
      lists = ['daily_articles', 'selected_articles', 'article_results']

      angular.forEach lists, (list) ->
        angular.forEach $scope[list], (article)->
          if article.id == message.id
            article.read.push (new moment()).format('YYYY-MM-DD')
      $scope.articles_read_today += 1
      $scope.removeArticleFromList(message, 'daily_articles')

    $scope.$on 'userLoggedIn', ->
      $scope.initDashboard()

    $scope.$on 'userLoggedOut', ->
      $scope.initDashboard()

    $scope.$on 'closeModal', ->
      $scope.bookModalOpen = false
      $scope.gameModalOpen = false
      $scope.songModalOpen = false

    $scope.$watch 'display_category', (newValue)->
      if newValue != ''
        $scope.getArticlesForCategory newValue, 10, true, false, true

    $scope.$watch 'article_search', (newValue)->
      if newValue
        $scope.searching = true
        $scope.search_articles()
      else
        $scope.article_results = []

    $scope.$watch 'is_read', (newValue, oldValue)->
      if newValue != oldValue && $scope.article_search
        $scope.search_articles()

    $scope.$watch 'bookModalOpen', ->
      if !$scope.bookModalOpen
        $scope.newBook = new Book()

    $scope.$watch 'gameModalOpen', ->
      if !$scope.gameModalOpen
        $scope.newGame = new Game()

    $scope.$watch 'songModalOpen', ->
      if !$scope.songModalOpen
        $scope.newSong = new Song()

    $scope.getArticlesForCategory = (category, limit, randomize, update_load_count)->
      $scope.loading_category = true
      $scope.selected_articles = []
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
        $scope.selected_articles = articles

      categoryArticlesPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not load articles for category'

      categoryArticlesPromise.finally ->
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
      $scope.loading_category = true
      $scope.loading_tasks = true
      articles_promise = ArticleResource.daily().$promise

      articles_promise.then (articles)->
        $scope.daily_articles = articles.filter (article)->
          !article.readToday() && !article.postponedToday()
        $scope.loading_category = false

      daily_promise = DashboardResource.get().$promise

      daily_promise.then (response)->
        $scope.articles_read_today = response.articles_read_today
        $scope.categories = response.categories
        $scope.total_articles = response.total_articles
        $scope.articles_read = response.articles_read
        $scope.total_books = response.total_books
        $scope.books_read = response.books_read
        $scope.books_toread = response.books_toread
        $scope.games_toplay = response.games_toplay
        $scope.songs_toplay = response.songs_toplay
        $scope.display_categories = response.dashboard_layout

      daily_promise.catch ->
        $scope.$emit 'errorOccurred', 'Problem loading daily articles'

    $scope.populateLinks = ->
      populate_promise = ArticleResource.populate().$promise
      populate_promise.then ->
        $scope.loadDashboard()

    $scope.refreshReadCount = ->
      $scope.updating_status = true
      readCount_promise = ArticleResource.readToday().$promise

      readCount_promise.then (response)->
        $scope.articles_read_today = response.articles_read_today

      readCount_promise.finally ->
        $scope.updating_status = false

    $scope.removeArticleFromList = (article, list)->
      $scope[list] = $scope[list].filter (list_article)->
        list_article.id != article.id

    $scope.filterDeletedArticles = (list)->
      if $scope[list]
        deleted_articles = $scope[list].filter (article)->
          article.deleted
        if deleted_articles.length > 0
          deleted_articles.forEach (article)->
            $scope.removeArticleFromList(article, list)

    $scope.postponeArticle = (article)->
      article.postpone().then ->
        $scope.removeArticleFromList(article, 'daily_articles')

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
