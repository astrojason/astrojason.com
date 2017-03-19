describe 'DashboardController tests', ->
  $scope = null
  DashboardController = null
  mockArticleResource = null
  mockArticleResourceDailyDeferred = null

  beforeEach ->
    module 'astroApp'

    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockArticleResource =
        daily: ->
          mockArticleResourceDailyDeferred = $q.defer()
          $promise: mockArticleResourceDailyDeferred.promise

      mockInjections =
        $scope: $scope
        ArticleResource: mockArticleResource

      DashboardController = $controller 'DashboardController', mockInjections

  it 'the default value for $scope.loadingArticles should be false', ->
    expect($scope.loadingArticles).toBe false

  it 'the default value for $scope.loadArticlesError should be false', ->
    expect($scope.loadArticlesError).toBe false

  it 'the default value of articles should be an empty array', ->
    expect($scope.articles).toEqual []

  it 'should call $scope.getDailyArticles when $scope.init is called', ->
    spyOn $scope, 'getDailyArticles'
    $scope.init()
    expect($scope.getDailyArticles).toHaveBeenCalled()

  it '$scope.loadingArticles should be set to true when $scope.getDailyArticles is called', ->
    $scope.getDailyArticles()
    expect($scope.loadingArticles).toBe true

  it '$scope.loadArticlesError should be set to false when $scope.getDailyArticles is called', ->
    $scope.loadArticlesError = true
    $scope.getDailyArticles()
    expect($scope.loadArticlesError).toBe false

  it 'ArticleResource.daily should be called when $scope.getDailyArticles is called', ->
    spyOn(mockArticleResource, 'daily').and.callThrough()
    $scope.getDailyArticles()
    expect(mockArticleResource.daily).toHaveBeenCalled()

  it 'should call $scope.watchArticles when ArticleResource.daily succeeds', ->
    spyOn $scope, 'watchArticles'
    $scope.getDailyArticles()
    mockArticleResourceDailyDeferred.resolve [1,2,3,4]
    $scope.$digest()
    expect($scope.watchArticles).toHaveBeenCalled()

  it '$scope.loadingArticles should be false when ArticleResource.daily succeeds', ->
    spyOn $scope, 'watchArticles'
    $scope.getDailyArticles()
    mockArticleResourceDailyDeferred.resolve [1,2,3,4]
    $scope.$digest()
    expect($scope.loadingArticles).toBe false

  it '$scope.loadArticlesError should be true when ArticleResource.daily fails', ->
    $scope.getDailyArticles()
    mockArticleResourceDailyDeferred.reject()
    $scope.$digest()
    expect($scope.loadArticlesError).toBe true

  it '$scope.loadingArticles should be false when ArticleResource.daily fails', ->
    $scope.getDailyArticles()
    mockArticleResourceDailyDeferred.reject()
    $scope.$digest()
    expect($scope.loadingArticles).toBe false