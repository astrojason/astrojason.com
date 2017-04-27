describe 'ArticleController unit tests', ->
  $scope = null
  $compile = null
  $timeout = null
  ArticleController = null
  Link = null
  scopeLink = null
  mockLinkResource = null
  mockArticlesResource = null
  mockAlertifyService = null
  mockLinkQueryDeferred = null
  mockLinkSaveDeferred = null
  mockLinkRemoveDeferred = null
  mockLinkQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/links.json'
  mockArticleImportDeferred = null
  mockForm = """
    <form name="link_form">
      <input name="name" ng-model="link.name" />
    </form>
  """

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q, _$compile_, _$timeout_, _Link_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $compile = _$compile_
      Link = _Link_

      mockLinkResource =
        query: ->
          mockLinkQueryDeferred = $q.defer()
          $promise: mockLinkQueryDeferred.promise

        save: ->
          mockLinkSaveDeferred = $q.defer()
          $promise: mockLinkSaveDeferred.promise

        remove: ->
          mockLinkRemoveDeferred = $q.defer()
          $promise: mockLinkRemoveDeferred.promise

      mockArticlesResource =
        import: ->
          mockArticleImportDeferred = $q.defer()
          $promise: mockArticleImportDeferred.promise

      mockAlertifyService =
        success: ->
        error: ->

      mockInjections =
        $scope: $scope
        LinkResource: mockLinkResource
        AlertifyService: mockAlertifyService
        ArticleResource: mockArticlesResource

      ArticleController = $controller 'ArticleController', mockInjections

      scopeLink = angular.copy mockLinkQueryResponse.links[0]

  it 'should set the default values', ->
    expect($scope.deleting).toEqual false
    expect($scope.errorMessage).toEqual false
    expect($scope.importedCount).toEqual 0
    expect($scope.loading_links).toEqual false

  it 'should filter out the deleted scopeLink from $scope.links_list when linkDeleted is broadcast', ->
    $scope.links_list = angular.copy mockLinkQueryResponse.links
    $scope.$broadcast 'linkDeleted', scopeLink.id
    $scope.$digest()
    expect($scope.links_list[0]).not.toEqual scopeLink

  it 'should filter out the deleted scopeLink from $scope.link_results when linkDeleted is broadcast', ->
    $scope.link_results = angular.copy mockLinkQueryResponse.links
    $scope.$broadcast 'linkDeleted', scopeLink.id
    $scope.$digest()
    expect($scope.link_results[0]).not.toEqual scopeLink

  it 'should set $scope.errorMessage to false when scopeLink.scopeLink is changed', ->
    $scope.$digest()
    $scope.errorMessage = true
    $scope.link =
      link: 'http://www.google.com'
    $scope.$digest()
    expect($scope.errorMessage).toEqual false

  it 'should not set $scope.errorMessage to false when scopeLink.scopeLink is not changed', ->
    $scope.link =
      link: 'http://www.google.com'
    $scope.$digest()
    $scope.errorMessage = true
    $scope.link =
      link: 'http://www.google.com'
    $scope.$digest()
    expect($scope.errorMessage).toEqual true

  it 'should set $scope.scopeLink to a new instance of Link when $scope.init is called', ->
    $scope.initList()
    expect($scope.newLink).toEqual new Link()

  it 'should call $scope.query when $scope.initList is called', ->
    spyOn $scope, 'query'
    $scope.initList()
    expect($scope.query).toHaveBeenCalled()

  it 'should not handle the closeModal event if $scope.initList has not been called', ->
    $scope.linkModalOpen = true
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.linkModalOpen).toEqual true

  it 'should handle the closeModal event if $scope.initList has been called', ->
    $scope.linkModalOpen = true
    $scope.initList()
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.linkModalOpen).toEqual false

  it 'should not call $scope.query if $scope.link_query is changed and $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.link_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initList has been called, $scope.link_query is changed and $scope.loading_links is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_links = true
    $scope.link_query = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.link_query is changed and $scope.initList has been called and $scope.loading_links is false', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.link_query = 'changed'
    $scope.$digest()
    $timeout.flush()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query if $scope.page is changed and $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.$digest()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not call $scope.query if $scope.initList has been called, $scope.page is changed and $scope.loading_links is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_links = true
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.page is changed and $scope.initList has been called and $scope.loading_links is false', ->
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

  it 'should not call $scope.query if $scope.initList has been called, $scope.display_category is changed and $scope.loading_links is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_links = true
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query if $scope.display_category is changed and $scope.initList has been called and $scope.loading_links is false', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.display_category = 'changed'
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should set $scope.loading_links to true when $scope.query is called', ->
    $scope.query()
    expect($scope.loading_links).toEqual true

  it 'should pass the default parameters to LinkResource.query if the optional values are not set', ->
    spyOn(mockLinkResource, 'query').and.callThrough()
    $scope.query()
    expect(mockLinkResource.query).toHaveBeenCalledWith
      limit: $scope.limit
      page: $scope.page

  it 'should pass the q parameter to LinkResource.query when $scope.links_query is set', ->
    spyOn(mockLinkResource, 'query').and.callThrough()
    $scope.links_query = 'test'
    $scope.query()
    expect(mockLinkResource.query).toHaveBeenCalledWith
      limit: $scope.limit
      page: $scope.page
      q: 'test'

  it 'should pass the category parameter to LinkResource.query when $scope.display_category is set', ->
    spyOn(mockLinkResource, 'query').and.callThrough()
    $scope.display_category = 'test'
    $scope.query()
    expect(mockLinkResource.query).toHaveBeenCalledWith
      limit: $scope.limit
      page: $scope.page
      category: 'test'

  it 'should set $scope.loading_links to false when LinkResource.query succeeds', ->
    $scope.query()
    mockLinkQueryDeferred.resolve angular.copy(mockLinkQueryResponse)
    $scope.$digest()
    expect($scope.loading_links).toEqual false

  it 'should set $scope.links to the returned value when LinkResource.query succeeds', ->
    $scope.query()
    mockLinkQueryDeferred.resolve angular.copy(mockLinkQueryResponse.links)
    $scope.$digest()
    expect($scope.links_list).toEqual mockLinkQueryResponse.links

  it 'should set $scope.total to the returned value when LinkResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockLinkQueryResponse.links)
    response.$total = 10
    mockLinkQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.total).toEqual 10

  it 'should set $scope.pages to the returned value when LinkResource.query succeeds', ->
    $scope.query()
    response = angular.copy(mockLinkQueryResponse.links)
    response.$pages = 10
    mockLinkQueryDeferred.resolve response
    $scope.$digest()
    expect($scope.pages).toEqual 10

  it 'should call $scope.generatePages when LinkResource.query succeeds', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockLinkQueryDeferred.resolve angular.copy(mockLinkQueryResponse.links)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should set $scope.loading_links to false when LinkResource.query fails', ->
    $scope.query()
    mockLinkQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_links).toEqual false

  it 'should increment the links total when $scope.linkOpened is called', ->
    $scope.link = scopeLink
    currentTotal = $scope.link.times_read
    $scope.linkOpened()
    expect($scope.link.times_read).toEqual currentTotal + 1

  it 'should call $scope.save when $scope.linkOpened is called', ->
    spyOn $scope, 'save'
    $scope.link = scopeLink
    $scope.linkOpened()
    expect($scope.save).toHaveBeenCalled()

  it 'should toggle the read status of the current scopeLink when $scope.toggleRead is called', ->
    $scope.link = scopeLink
    $scope.link.is_read = false
    $scope.toggleRead()
    expect($scope.link.is_read).toEqual true
    $scope.toggleRead()
    expect($scope.link.is_read).toEqual false

  it 'should call $scope.save when $scope.toggleRead is called', ->
    spyOn $scope, 'save'
    $scope.link = scopeLink
    $scope.toggleRead()
    expect($scope.save).toHaveBeenCalled()

  it 'should call LinkResource.remove when $scope.delete is called', ->
    spyOn(mockLinkResource, 'remove').and.callThrough()
    $scope.link = scopeLink
    $scope.delete()
    expect(mockLinkResource.remove).toHaveBeenCalledWith id: $scope.link.id

  it 'should set call AlertifyService.success when LinkResource.remove succeeds', ->
    spyOn mockAlertifyService, 'success'
    $scope.link = scopeLink
    $scope.delete()
    mockLinkRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith "Link deleted successfully"

  it 'should set $scope.errorMessage to the returned value when one is passed', ->
    $scope.link = scopeLink
    $scope.delete()
    mockLinkRemoveDeferred.reject data: error: 'This is my passed error.'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is my passed error.'

  it 'should set $scope.errorMessage to the default value when one is passed', ->
    $scope.link = scopeLink
    $scope.delete()
    mockLinkRemoveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was an error deleting the selected link.'

  it 'should set the display category to the new category', ->
    $scope.link =
      category: 'New'
    $scope.new_category = 'My New Category'
    $scope.save()
    expect($scope.link.category).toEqual 'My New Category'

  it 'should call LinkResource.save when $scope.save is called', ->
    spyOn(mockLinkResource, 'save').and.callThrough()
    $scope.link = scopeLink
    $scope.save()
    expect(mockLinkResource.save).toHaveBeenCalledWith $scope.link

  it 'should call Alertify.success with the updated message when the LinkResource.save succeeds and an existing scopeLink is updated', ->
    spyOn mockAlertifyService, 'success'
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Link updated successfully.'

  it 'should call Alertify.success with the added message when the LinkResource.save succeeds and an existing scopeLink is added', ->
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    spyOn mockAlertifyService, 'success'
    $scope.link = angular.copy(scopeLink)
    $scope.link.id = null
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Link added successfully.'

  it 'should set $scope.editing to false when LinkResource.save succeeds', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should $emit the linkUpdated event when LinkResource.save succeeds', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'linkUpdated', $scope.link

  it 'should not $emit the linkUpdated event when LinkResource.save fails', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).not.toHaveBeenCalled()

  it 'should $emit the linkRead event when LinkResource.save succeeds and the read status has changed', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = angular.copy scopeLink
    $scope.link.is_read = !$scope.link.is_read
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'linkRead', $scope.link.is_read

  it 'should not $emit the linkRead event when LinkResource.save succeeds and the read status has not changed', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = angular.copy scopeLink
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.$emit).not.toHaveBeenCalledWith 'linkRead', $scope.link.is_read

  it 'should not set $scope.editing to false when LinkResource.save fails', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.reject()
    $scope.$digest()
    expect($scope.editing).toEqual true

  it 'should set the form to pristine when it is a new link and the LinkResource.save succeeds', ->
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = new Link()
    $scope.link_form.name.$setViewValue 'New Name'
    $scope.$digest()
    expect($scope.link_form.$pristine).toEqual false
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.link_form.$pristine).toEqual true

  it 'should close the modal when it is a new link and the LinkResource.save succeeds', ->
    spyOn $scope, '$emit'
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = new Link()
    $scope.save()
    mockLinkSaveDeferred.resolve link: angular.copy(scopeLink)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal', scopeLink

  it 'should set $scope.errorMessage to the default value when LinkResource.save fails and no error is returned', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was an error saving the selected link.'

  it 'should set $scope.errorMessage to the passed message when LinkResource.save fails and an error is returned', ->
    $scope.editing = true
    element = angular.element mockForm
    linker = $compile element
    element = linker $scope
    $scope.link = scopeLink
    $scope.originalLink = scopeLink
    $scope.save()
    mockLinkSaveDeferred.reject data: error: 'This is a passed error.'
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