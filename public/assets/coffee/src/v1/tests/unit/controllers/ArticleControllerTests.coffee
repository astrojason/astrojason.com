describe 'ArticleController unit tests', ->
  $scope = null
  $compile = null
  $timeout = null
  ArticleController = null
  mockArticlesResource = null
  mockAlertifyService = null
  mockArticleQueryDeferred = null
  mockArticleSaveDeferred = null
  mockArticleRemoveDeferred = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/articles.json'
  mockArticleImportDeferred = null
  mockForm = """
    <form name="link_form">
      <input name="name" ng-model="link.name" />
    </form>
  """

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q, _$compile_, _$timeout_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $compile = _$compile_

      mockArticlesResource =
        import: ->
          mockArticleImportDeferred = $q.defer()
          $promise: mockArticleImportDeferred.promise

      mockAlertifyService =
        success: ->
        error: ->

      mockInjections =
        $scope: $scope
        AlertifyService: mockAlertifyService
        ArticleResource: mockArticlesResource

      ArticleController = $controller 'ArticleController', mockInjections

      $scope.article = angular.copy mockArticleQueryResponse.articles[0]

  it 'should set the default values', ->
    expect($scope.deleting).toEqual false
    expect($scope.errorMessage).toEqual false
    expect($scope.importedCount).toEqual 0
    expect($scope.loading_articles).toEqual false

  it 'should not call $scope.query if $scope.article_query is changed and $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.article_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initList has been called, $scope.article_query is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.article_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.article_query is changed and $scope.initList has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.article_query = 'changed'
    $scope.$digest()
    $timeout.flush()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query if $scope.page is changed and $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initList has been called, $scope.page is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.page is changed and $scope.initList has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query if $scope.display_category is changed and $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initList has been called, $scope.display_category is changed and $scope.loading_articles is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_articles = true
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.display_category is changed and $scope.initList has been called and $scope.loading_articles is false', ->
    spyOn $scope, 'query'
    $scope.initList()
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
      limit: $scope.limit
      page: $scope.page

  it 'should pass the q parameter to ArticleResource.query when $scope.articles_query is set', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.articles_query = 'test'
    $scope.query()
    expect(mockArticleResource.query).toHaveBeenCalledWith
      limit: $scope.limit
      page: $scope.page
      q: 'test'

  it 'should pass the category parameter to ArticleResource.query when $scope.display_category is set', ->
    spyOn(mockArticleResource, 'query').and.callThrough()
    $scope.display_category = 'test'
    $scope.query()
    expect(mockArticleResource.query).toHaveBeenCalledWith
      limit: $scope.limit
      page: $scope.page
      category: 'test'

  it 'should set $scope.loading_articles to false when ArticleResource.query succeeds', ->
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse)
    $scope.$digest()
    expect($scope.loading_articles).toEqual false

  it 'should set $scope.articles to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse.links)
    $scope.$digest()
    expect($scope.articles_list).toEqual mockArticleQueryResponse.links

  it 'should set $scope.total to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockArticleQueryResponse.links)
    response.$total = 10
    mockArticleQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.total).toEqual 10

  it 'should set $scope.pages to the returned value when ArticleResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockArticleQueryResponse.links)
    response.$pages = 10
    mockArticleQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.pages).toEqual 10

  it 'should call $scope.generatePages when ArticleResource.query succeeds', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockArticleQueryDeferred.resolve angular.copy(mockArticleQueryResponse.links)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should set $scope.loading_articles to false when ArticleResource.query fails', ->
    $scope.query()
    mockArticleQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_articles).toEqual false

  it 'should increment the links total when $scope.articleOpened is called', ->
    $scope.article = $scope.article
    currentTotal = $scope.article.times_read
    $scope.articleOpened()
    expect($scope.article.times_read).toEqual currentTotal + 1

  it 'should call $scope.save when $scope.articleOpened is called', ->
    spyOn $scope, 'save'
    $scope.article = $scope.article
    $scope.articleOpened()
    expect($scope.save).toHaveBeenCalled()

  it 'should toggle the read status of the current $scope.article when $scope.toggleRead is called', ->
    $scope.article = $scope.article
    $scope.article.is_read = false
    $scope.toggleRead()
    expect($scope.article.is_read).toEqual true
    $scope.toggleRead()
    expect($scope.article.is_read).toEqual false

  it 'should call $scope.save when $scope.toggleRead is called', ->
    spyOn $scope, 'save'
    $scope.article = $scope.article
    $scope.toggleRead()
    expect($scope.save).toHaveBeenCalled()

  it 'should call ArticleResource.remove when $scope.delete is called', ->
    spyOn(mockArticleResource, 'remove').and.callThrough()
    $scope.article = $scope.article
    $scope.delete()
    expect(mockArticleResource.remove).toHaveBeenCalledWith id: $scope.article.id

  it 'should set call AlertifyService.success when ArticleResource.remove succeeds', ->
    spyOn mockAlertifyService, 'success'
    $scope.article = $scope.article
    $scope.delete()
    mockArticleRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith "Link deleted successfully"

  it 'should set $scope.errorMessage to the returned value when one is passed', ->
    $scope.article = $scope.article
    $scope.delete()
    mockArticleRemoveDeferred.reject data: error: 'This is my passed error.'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is my passed error.'

  it 'should set $scope.errorMessage to the default value when one is passed', ->
    $scope.article = $scope.article
    $scope.delete()
    mockArticleRemoveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was an error deleting the selected link.'

  it 'should set the display category to the new category', ->
    $scope.article =
      category: 'New'
    $scope.new_category = 'My New Category'
    $scope.save()
    expect($scope.article.category).toEqual 'My New Category'

  it 'should call ArticleResource.save when $scope.save is called', ->
    spyOn(mockArticleResource, 'save').and.callThrough()
    $scope.article = $scope.article
    $scope.save()
    expect(mockArticleResource.save).toHaveBeenCalledWith $scope.article

  it 'should call Alertify.success with the updated message when the ArticleResource.save succeeds and an existing $scope.article is updated', ->
    spyOn mockAlertifyService, 'success'
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Link updated successfully.'

  it 'should call Alertify.success with the added message when the ArticleResource.save succeeds and an existing $scope.article is added', ->
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    spyOn mockAlertifyService, 'success'
    $scope.article = angular.copy($scope.article)
    $scope.article.id = null
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Link added successfully.'

  it 'should set $scope.editing to false when ArticleResource.save succeeds', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should $emit the linkUpdated event when ArticleResource.save succeeds', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'linkUpdated', $scope.article

  it 'should not $emit the linkUpdated event when ArticleResource.save fails', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).not.toHaveBeenCalled()

  it 'should $emit the linkRead event when ArticleResource.save succeeds and the read status has changed', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = angular.copy $scope.article
    $scope.article.is_read = !$scope.article.is_read
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'linkRead', $scope.article.is_read

  it 'should not $emit the linkRead event when ArticleResource.save succeeds and the read status has not changed', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = angular.copy $scope.article
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.$emit).not.toHaveBeenCalledWith 'linkRead', $scope.article.is_read

  it 'should not set $scope.editing to false when ArticleResource.save fails', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.reject()
    $scope.$digest()
    expect($scope.editing).toEqual true

  it 'should set the form to pristine when it is a new link and the ArticleResource.save succeeds', ->
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = new Link()
    $scope.article_form.name.$setViewValue 'New Name'
    $scope.$digest()
    expect($scope.article_form.$pristine).toEqual false
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.article_form.$pristine).toEqual true

  it 'should close the modal when it is a new link and the ArticleResource.save succeeds', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = new Link()
    $scope.save()
    mockArticleSaveDeferred.resolve link: angular.copy($scope.article)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal', $scope.article

  it 'should set $scope.errorMessage to the default value when ArticleResource.save fails and no error is returned', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was an error saving the selected link.'

  it 'should set $scope.errorMessage to the passed message when ArticleResource.save fails and an error is returned', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.article = $scope.article
    $scope.originalLink = $scope.article
    $scope.save()
    mockArticleSaveDeferred.reject data: error: 'This is a passed error.'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed error.'

  it 'should set $scope.categories to the passed categories', ->
    myCategories = [1,2,5,6,8,9]
    $scope.setCategories(myCategories)
    expect($scope.categories).toEqual myCategories

  it 'should call ArticleResource.import when importLinks is called', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    spyOn(mockArticlesResource, 'import').and.callThrough()
    $scope.importLinks()
    expect(mockArticlesResource.import).toHaveBeenCalled()

  it 'should set the number of imported items when ArticleResource.import succeeds', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    $scope.importLinks()
    mockArticleImportDeferred.resolve [
        justAdded: true
      ,
        justAdded: true
      ]
    $scope.$digest()
    expect($scope.alerts).toEqual [
      type: 'success'
      msg: "Imported 2 link(s)."
    ]

  it 'should set the errorMessage when ArticleResource.import fails', ->
    spyOn($scope, 'splitImports').and.returnValue [
      'http://www.google.com | Google'
      'http://www.goodreads.com | Goodreads'
    ]
    $scope.importLinks()
    mockArticleImportDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem with the import'

  it 'should split the string by http', ->
    data = 'http://www.google.com | Googlehttp://www.goodreads.com | Goodreads'
    expect($scope.splitImports(data)).toEqual ['', '://www.google.com | Google', '://www.goodreads.com | Goodreads']
