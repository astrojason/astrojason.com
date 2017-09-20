describe 'DashboardController tests', ->
  $scope = null
  $timeout = null
  $httpBackend = null
  DashboardController = null
  mockArticleResource = null
  mockArticleQuery = null
  mockArticleDailyQuery = null
  mockArticlePopulate = null
  mockArticleReadToday = null
  mockDashboardGet = null
  mockUserService = null
  mockDashboardResource = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/articles.json'
  mockDashboardQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/dashboard.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, _$httpBackend_, $q, _ArticleResource_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $httpBackend = _$httpBackend_
      mockArticleResource = _ArticleResource_

      spyOn(mockArticleResource, 'query').and.callFake(() ->
        mockArticleQuery = $q.defer()
        $promise: mockArticleQuery.promise
      )

      spyOn(mockArticleResource, 'daily').and.callFake(() ->
        mockArticleDailyQuery = $q.defer()
        $promise: mockArticleDailyQuery.promise
      )

      spyOn(mockArticleResource, 'populate').and.callFake(() ->
        mockArticlePopulate = $q.defer()
        $promise: mockArticlePopulate.promise
      )

      spyOn(mockArticleResource, 'readToday').and.callFake(() ->
        mockArticleReadToday = $q.defer()
        $promise: mockArticleReadToday.promise
      )

      mockUserService =
        get: ->

      mockDashboardResource =
        get: ->
          mockDashboardGet = $q.defer()
          $promise: mockDashboardGet.promise

      mockInjections =
        $scope: $scope
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

  it 'should call getArticlesForCategory when display_category is changed and it has a value', ->
    $scope.initWatchers()
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
    $scope.initWatchers()
    $scope.article_search = 'test'
    $scope.$digest()
    expect($scope.searching).toEqual true

  it 'should call $scope.search_articles if article_search is long enough', ->
    $scope.initWatchers()
    spyOn($scope, 'search_articles').and.returnValue true
    $scope.article_search = 'test'
    $scope.$digest()
    #    Make sure there is a timeout pending
    $timeout ->
    $timeout.flush()
    expect($scope.search_articles).toHaveBeenCalled()

  it 'should call $scope.search_articles when there is a long enough search term and is_read is changed', ->
    $scope.initWatchers()
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
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.article_results).toEqual mockArticleQueryResponse.articles

  it 'should set $scope.searching to false when ArticleResource.query succeeds', ->
    $scope.search_articles()
    mockArticleQuery.resolve angular.copy(mockArticleQueryResponse.articles)
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

  it 'should set the articles_read_today value to what is returned from DashboardResource.get on success', ->
    $scope.loadDashboard()
    mockDashboardGet.resolve angular.copy(mockDashboardQueryResponse)
    $scope.$digest()
    expect($scope.articles_read_today).toEqual mockDashboardQueryResponse.articles_read_today

  it 'should set the categories value to what is returned from DashboardResource.get on success', ->
    $scope.loadDashboard()
    mockDashboardGet.resolve angular.copy(mockDashboardQueryResponse)
    $scope.$digest()
    expect($scope.categories).toEqual mockDashboardQueryResponse.categories

  it 'should set the total_articles value to what is returned from DashboardResource.get on success', ->
    $scope.loadDashboard()
    mockDashboardGet.resolve angular.copy(mockDashboardQueryResponse)
    $scope.$digest()
    expect($scope.total_articles).toEqual mockDashboardQueryResponse.total_articles

  it 'should set the articles_read value to what is returned from DashboardResource.get on success', ->
    $scope.loadDashboard()
    mockDashboardGet.resolve angular.copy(mockDashboardQueryResponse)
    $scope.$digest()
    expect($scope.articles_read).toEqual mockDashboardQueryResponse.articles_read

  it 'should emit an error when Dashboard.get fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.loadDashboard()
    mockDashboardGet.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Problem loading daily articles'

  it 'should call ArticleResource.populate when populateLinks is called', ->
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

  it 'should call ArticleResource.readToday when $scope.refreshReadCount is called', ->
    $scope.refreshReadCount()
    expect(mockArticleResource.readToday).toHaveBeenCalled()