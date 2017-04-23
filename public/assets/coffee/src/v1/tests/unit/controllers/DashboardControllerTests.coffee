describe 'DashboardController tests', ->
  $scope = null
  $timeout = null
  $httpBackend = null
  DashboardController = null
  mockLinkResource = null
  mockArticleResource = null
  mockArticleQuery = null
  mockArticleDailyQuery = null
  mockArticlePopulate = null
  mockLinkReadToday = null
  mockDashboardGet = null
  mockUserService = null
  mockDashboardResource = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/articles.json'
  mockDashboardQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/dashboard.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, _$httpBackend_, $q)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $httpBackend = _$httpBackend_

      mockLinkResource =
        readToday: ->
          mockLinkReadToday = $q.defer()
          $promise: mockLinkReadToday.promise

      mockArticleResource =
        query: ->
          mockArticleQuery = $q.defer()
          $promise: mockArticleQuery.promise
        daily: ->
          mockArticleDailyQuery = $q.defer()
          $promise: mockArticleDailyQuery.promise
        populate: ->
          mockArticlePopulate = $q.defer()
          $promise: mockArticlePopulate.promise

      mockUserService =
        get: ->

      mockDashboardResource =
        get: ->
          mockDashboardGet = $q.defer()
          $promise: mockDashboardGet.promise

      mockInjections =
        $scope: $scope
        LinkResource: mockLinkResource
        UserService: mockUserService
        DashboardResource: mockDashboardResource
        ArticleResource: mockArticleResource

      DashboardController = $controller 'DashboardController', mockInjections

  it 'should set display_category to the default value', ->
    expect($scope.display_category).toEqual ''

  it 'should set search_timeout to the default value', ->
    expect($scope.search_timeout).toEqual null

  it 'should set article_results to the default value', ->
    expect($scope.article_results).toEqual []

  it 'should set loading_unread to the default value', ->
    expect($scope.loading_unread).toEqual false

  it 'should set loading_category to the default value', ->
    expect($scope.loading_category).toEqual false

  it 'should set recommendingBook to the default value', ->
    expect($scope.recommendingBook).toEqual false

  it 'should set recommendingGame to the default value', ->
    expect($scope.recommendingGame).toEqual false

  it 'should set recommendingSong to the default value', ->
    expect($scope.recommendingSong).toEqual false

  it 'should set bookModalOpen to the default value', ->
    expect($scope.bookModalOpen).toEqual false

  it 'should set gameModalOpen to the default value', ->
    expect($scope.gameModalOpen).toEqual false

  it 'should set songModalOpen to the default value', ->
    expect($scope.songModalOpen).toEqual false

  it 'should call initDashboard when userLoggedIn is broadcast', ->
    spyOn($scope, 'initDashboard').and.returnValue true
    $scope.$broadcast 'userLoggedIn'
    $scope.$digest()
    expect($scope.initDashboard).toHaveBeenCalled()

  it 'should call initDashboard when userLoggedOut is broadcast', ->
    spyOn($scope, 'initDashboard').and.returnValue true
    $scope.$broadcast 'userLoggedOut'
    $scope.$digest()
    expect($scope.initDashboard).toHaveBeenCalled()

  it 'should set bookModalOpen to false when closeModal is broadcast', ->
    $scope.bookModalOpen = true
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.bookModalOpen).toEqual false

  it 'should set gameModalOpen to false when closeModal is broadcast', ->
    $scope.gameModalOpen = true
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.gameModalOpen).toEqual false

  it 'should set songModalOpen to false when closeModal is broadcast', ->
    $scope.songModalOpen = true
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.songModalOpen).toEqual false

  it 'should call getArticlesForCategory when display_category is changed and it has a value', ->
    spyOn($scope, 'getArticlesForCategory').and.returnValue true
    $scope.display_category = 'Test'
    $scope.$digest()
    expect($scope.getArticlesForCategory).toHaveBeenCalled()

  it 'should call getArticlesForCategory when display_category is changed and has no value', ->
    spyOn($scope, 'getArticlesForCategory').and.returnValue true
    $scope.display_category = ''
    $scope.$digest()
    expect($scope.getArticlesForCategory).not.toHaveBeenCalled()

  it 'should set $scope.searching to true when article_search changes', ->
    $scope.article_search = 'test'
    $scope.$digest()
    expect($scope.searching).toEqual true

  it 'should call $scope.search_articles if article_search is long enough', ->
    spyOn($scope, 'search_articles').and.returnValue true
    $scope.article_search = 'test'
    $scope.$digest()
    #    Make sure there is a timeout pending
    $timeout ->
    $timeout.flush()
    expect($scope.search_articles).toHaveBeenCalled()

  it 'should not call $scope.search_articles if article_search is not long enough', ->
    spyOn($scope, 'search_articles').and.returnValue true
    $scope.article_search = 'te'
    $scope.$digest()
    #    This is necessary since there will be no timeout created if the code works properly
    $timeout ->
    $timeout.flush()
    expect($scope.search_articles).not.toHaveBeenCalled()

  it 'should call $scope.search_articles when there is a long enough search term and is_read is changed', ->
    spyOn($scope, 'search_articles').and.returnValue true
    $scope.article_search = 'test'
    $scope.$digest()
    #    Make sure there is a timeout pending
    $timeout ->
    $timeout.flush()
    $scope.search_articles.calls.reset()
    $scope.is_read = true
    $scope.$digest()
    #    Make sure there is a timeout pending
    $timeout ->
    $timeout.flush()
    expect($scope.search_articles).toHaveBeenCalled()

  it 'should set the base variables when $scope.getArticlesForCategory is called for a selection category', ->
    $scope.selected_articles = ['test']
    $scope.getArticlesForCategory 'Test Category', 10, true, false, true
    expect($scope.selected_articles).toEqual []
    expect($scope.loading_category).toEqual true

  it 'should call ArticleResource.query when $scope.getArticlesForCategory is called', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.getArticlesForCategory 'Daily'
    expect(mockArticleResource.query).toHaveBeenCalled()

  it 'should set $scope.selected_articles to the returned value', ->
    $scope.getArticlesForCategory 'Test Category', 10, true, false, true
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.selected_articles).toEqual mockArticleQueryResponse.articles

  it 'should set $scope.loading_category to false when ArticleResource.query succeeds', ->
    $scope.getArticlesForCategory 'Test Category', 10, true, false
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.loading_category to false when ArticleResource.query fails', ->
    $scope.getArticlesForCategory 'Test Category', 10, true, false
    mockArticleQuery.reject()
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set emit an error when ArticleResource.query fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.getArticlesForCategory 'Test Category', 10, true, false
    mockArticleQuery.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not load articles for category'

  it 'should set the appropriate variables when search_articles is called', ->
    $scope.article_results = ['test']
    $scope.search_articles()
    expect($scope.article_results).toEqual []
    expect($scope.searching).toEqual true

  it 'should set $scope.article_results to the returned values when ArticleResource.query succeeds', ->
    $scope.search_articles()
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.links)
    $scope.$digest()
    expect($scope.article_results).toEqual mockArticleQueryResponse.links

  it 'should set $scope.searching to false when ArticleResource.query succeeds', ->
    $scope.search_articles()
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.links)
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.searching to false when ArticleResource.query succeeds', ->
    $scope.search_articles()
    mockArticleQuery.reject()
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.$emit to be called when ArticleResource.query succeeds', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.search_articles()
    mockArticleQuery.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not get perform the search'

  it 'should call UserService.get when $scope.initDashboard is called', ->
    spyOn(mockUserService, 'get').and.callThrough()
    $scope.initDashboard()
    expect(mockUserService.get).toHaveBeenCalled()

  it 'should not call $scope.loadDashboard when $scope.initDashboard is called and UserService.get does not return a user', ->
    spyOn($scope, 'loadDashboard').and.callThrough()
    $scope.initDashboard()
    expect($scope.loadDashboard).not.toHaveBeenCalled()

  it 'should call $scope.loadDashboard when $scope.initDashboard is called and UserService.get does return a user', ->
    spyOn(mockUserService, 'get').and.returnValue id: 1
    spyOn($scope, 'loadDashboard').and.callThrough()
    $scope.initDashboard()
    expect($scope.loadDashboard).toHaveBeenCalled()

  it 'should call DashboardResource.get when $scope.loadDashboard() is called', ->
    spyOn(mockDashboardResource, 'get').and.callThrough()
    $scope.loadDashboard()
    expect(mockDashboardResource.get).toHaveBeenCalled()

  it 'should set the appropriate values to what is returned from DashboardResource.get on success', ->
    $scope.loadDashboard()
    mockDashboardGet.resolve angular.copy(mockDashboardQueryResponse)
    $scope.$digest()
    expect($scope.total_read).toEqual mockDashboardQueryResponse.total_read
    expect($scope.categories).toEqual mockDashboardQueryResponse.categories
    expect($scope.total_links).toEqual mockDashboardQueryResponse.total_links
    expect($scope.links_read).toEqual mockDashboardQueryResponse.links_read
    expect($scope.total_books).toEqual mockDashboardQueryResponse.total_books
    expect($scope.books_read).toEqual mockDashboardQueryResponse.books_read
    expect($scope.books_toread).toEqual mockDashboardQueryResponse.books_toread
    expect($scope.games_toplay).toEqual mockDashboardQueryResponse.games_toplay
    expect($scope.songs_toplay).toEqual mockDashboardQueryResponse.songs_toplay

  it 'should emit an error when Dashboard.get fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.loadDashboard()
    mockDashboardGet.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Problem loading daily results'

  it 'should call ArticleResource.populate when populateLinks is called', ->
    spyOn(mockArticleResource, 'populate').and.callThrough()
    $scope.populateLinks()
    expect(mockArticleResource.populate).toHaveBeenCalled()

  it 'should call loadDashboard when the ArticleResource.populate responds successfully', ->
    spyOn($scope, 'loadDashboard').and.callThrough()
    $scope.populateLinks()
    mockArticlePopulate.resolve()
    $scope.$digest()
    expect($scope.loadDashboard).toHaveBeenCalled()

    expect($scope.loadDashboard).toHaveBeenCalled()

  it 'should not call loadDashboard when the ArticleResource.populate responds unsuccessfully', ->
    spyOn($scope, 'loadDashboard').and.callThrough()
    $scope.populateLinks()
    mockArticlePopulate.reject()
    $scope.$digest()
    expect($scope.loadDashboard).not.toHaveBeenCalled()

  it 'should call LinkResource.readToday when $scope.refreshReadCount is called', ->
    spyOn(mockLinkResource, 'readToday').and.callThrough()
    $scope.refreshReadCount()
    expect(mockLinkResource.readToday).toHaveBeenCalled()