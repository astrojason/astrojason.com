describe 'ArticleController unit tests', ->
  $scope = null
  $compile = null
  $timeout = null
  ArticleController = null
  mockArticleResource = null
  mockAlertifyService = null
  mockArticleQueryDeferred = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/articles.json'
  mockArticleImportDeferred = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q, _$compile_, _$timeout_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $compile = _$compile_

      mockArticleResource =
        import: ->
          mockArticleImportDeferred = $q.defer()
          $promise: mockArticleImportDeferred.promise
        query: ->
          mockArticleQueryDeferred = $q.defer()
          $promise: mockArticleQueryDeferred.promise

      mockAlertifyService =
        success: ->
        error: ->

      mockInjections =
        $scope: $scope
        AlertifyService: mockAlertifyService
        ArticleResource: mockArticleResource

      ArticleController = $controller 'ArticleController', mockInjections

      $scope.article = angular.copy mockArticleQueryResponse.articles[0]

  it 'should set the default values', ->
    expect($scope.deleting).toEqual false
    expect($scope.errorMessage).toEqual false
    expect($scope.importedCount).toEqual 0
    expect($scope.loading_articles).toEqual false

  it 'should not call $scope.query if $scope.article_query is changed and $scope.initWatchers has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.article_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initWatchers has been called, $scope.article_query is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.article_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.article_query is changed and $scope.initWatchers has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.article_query = 'changed'
    $scope.$digest()
    $timeout.flush()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query if $scope.page is changed and $scope.initWatchers has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initWatchers has been called, $scope.page is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.page is changed and $scope.initWatchers has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query if $scope.display_category is changed and $scope.initWatchers has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initWatchers has been called, $scope.display_category is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.display_category is changed and $scope.initWatchers has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initWatchers()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should set $scope.loading_articles to true when $scope.query is called', ->
    $scope.query()
    expect($scope.loading_articles).toEqual true

  it 'should pass the default parameters to ArticleResource.query if the optional values are not set', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.query()
    expect(mockArticleResource.query).toHaveBeenCalledWith
      page_size: $scope.page_size
      page: $scope.page

  it 'should pass the q parameter to ArticleResource.query when $scope.articles_query is set', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.article_query = 'test'
    $scope.query()
    expect(mockArticleResource.query).toHaveBeenCalledWith
      page_size: $scope.page_size
      page: $scope.page
      q: 'test'

  it 'should pass the category parameter to ArticleResource.query when $scope.display_category is set', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.display_category =
      id: 13
    $scope.query()
    expect(mockArticleResource.query).toHaveBeenCalledWith
      page_size: $scope.page_size
      page: $scope.page
      category: 13

  it 'should set $scope.loading_articles to false when ArticleResource.query succeeds', ->
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.loading_articles).toEqual false

  it 'should set $scope.articles to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.articles).toEqual mockArticleQueryResponse.articles

  it 'should set $scope.total to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockArticleQueryResponse.articles)
    response.$total = 100
    mockArticleQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.total).toEqual 100

  it 'should set $scope.pages to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockArticleQueryResponse.articles)
    response.$page_count = 10
    mockArticleQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.pages).toEqual 10

  it 'should call $scope.generatePages when ArticleResource.query succeeds', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse.articles)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should set $scope.loading_articles to false when ArticleResource.query fails', ->
    $scope.query()
    mockArticleQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_articles).toEqual false

  it 'should call ArticleResource.import when importArticles is called', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    spyOn(mockArticleResource, 'import').and.callThrough()
    $scope.importArticles()
    expect(mockArticleResource.import).toHaveBeenCalled()

  it 'should set the number of imported items when ArticleResource.import succeeds', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    $scope.importArticles()
    mockArticleImportDeferred.resolve [
        justAdded: true
      ,
        justAdded: true
      ]
    $scope.$digest()
    expect($scope.alerts).toEqual [
      type: 'success'
      msg: "Imported 2 article(s)."
    ]

  it 'should set the errorMessage when ArticleResource.import fails', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    $scope.importArticles()
    mockArticleImportDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem with the import'

  it 'should split the string by http', ->
    data = 'http://www.google.com | Googlehttp://www.goodreads.com | Goodreads'
    expect($scope.splitImports(data)).toEqual ['', '://www.google.com | Google', '://www.goodreads.com | Goodreads']
