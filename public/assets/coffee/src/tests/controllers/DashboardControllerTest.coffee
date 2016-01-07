describe 'DashboardController tests', ->
  $scope = null
  $timeout = null
  DashboardController = null
  Link = null
  mockLinkResource = null
  mockLinkQuery = null
  mockUserService = null
  mockLinkQueryResponse = readJSON 'public/assets/coffee/src/tests/data/links.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, $q, _Link_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      Link = _Link_

      mockLinkResource =
        query: ->
          mockLinkQuery = $q.defer()
          $promise: mockLinkQuery.promise

      mockUserService =
        get: ->

      mockInjections =
        $scope: $scope
        LinkResource: mockLinkResource
        UserService: mockUserService

      DashboardController = $controller 'DashboardController', mockInjections

  it 'should set all the default variables', ->
    expect($scope.display_category).toEqual ''
    expect($scope.search_timeout).toEqual null
    expect($scope.daily_links).toEqual []
    expect($scope.selected_links).toEqual []
    expect($scope.link_results).toEqual []
    expect($scope.loading_unread).toEqual false
    expect($scope.recommendingBook).toEqual false
    expect($scope.recommendingGame).toEqual false
    expect($scope.recommendingSong).toEqual false
    expect($scope.linkModalOpen).toEqual false
    expect($scope.bookModalOpen).toEqual false
    expect($scope.movieModalOpen).toEqual false
    expect($scope.gameModalOpen).toEqual false
    expect($scope.songModalOpen).toEqual false
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

  it 'should call update the links when linkDeleted is broadcast', ->
    links = [{id: 1}, {id: 2}, {id: 3}]
    expected_links = [{id: 1}, {id: 3}]
    $scope.daily_links = links
    $scope.unread_links = links
    $scope.selected_links = links
    $scope.link_results = links
    $scope.$broadcast 'linkDeleted', 2
    $scope.$digest()
    expect($scope.daily_links).toEqual expected_links
    expect($scope.selected_links).toEqual expected_links
    expect($scope.unread_links).toEqual expected_links
    expect($scope.link_results).toEqual expected_links

  it 'should filter out the changed items in the daily list when linkUpdated is broadcast', ->
    links = [{category: 'Daily', is_read: false}, {category: 'Daily', is_read: true}, {category: 'Test', is_read: false}]
    expected_links = [{category: 'Daily', is_read: false}]
    $scope.daily_links = links
    $scope.$broadcast 'linkUpdated'
    $scope.$digest()
    expect($scope.daily_links).toEqual expected_links

  it 'should filter out the changed items in the unread list when linkUpdated is broadcast', ->
    links = [{category: 'Unread', is_read: false}, {category: 'Unread', is_read: true}, {category: 'Test', is_read: false}]
    expected_links = [{category: 'Unread', is_read: false}]
    $scope.unread_links = links
    $scope.$broadcast 'linkUpdated'
    $scope.$digest()
    expect($scope.unread_links).toEqual expected_links

  it 'should filter out the changed items in the unread list when linkUpdated is broadcast', ->
    spyOn($scope, 'getCategoryArticles').and.returnValue true
    $scope.display_category = 'Test'
    links = [{category: 'Unread', is_read: false}, {category: 'Unread', is_read: true}, {category: 'Test', is_read: false}]
    expected_links = [{category: 'Test', is_read: false}]
    $scope.selected_links = links
    $scope.$broadcast 'linkUpdated'
    $scope.$digest()
    expect($scope.selected_links).toEqual expected_links

  it 'should update the links_read and total_read valuse when linkRead is broadcast with a link id', ->
    $scope.links_read = 20
    $scope.total_read = 100
    $scope.$broadcast 'linkRead', 1
    expect($scope.links_read).toEqual 21
    expect($scope.total_read).toEqual 101

  it 'should update the links_read and total_read valuse when linkRead is broadcast with no link id', ->
    $scope.links_read = 20
    $scope.total_read = 100
    $scope.$broadcast 'linkRead'
    expect($scope.links_read).toEqual 19
    expect($scope.total_read).toEqual 99

  it 'should set all the closeModal variables to false when closeModal is broadcast', ->
    $scope.linkModalOpen = true
    $scope.bookModalOpen = true
    $scope.movieModalOpen = true
    $scope.gameModalOpen = true
    $scope.songModalOpen = true
    $scope.$broadcast 'closeModal'
    $scope.$digest()
    expect($scope.linkModalOpen).toEqual false
    expect($scope.bookModalOpen).toEqual false
    expect($scope.movieModalOpen).toEqual false
    expect($scope.gameModalOpen).toEqual false
    expect($scope.songModalOpen).toEqual false

  it 'should call getCategoryAricle when display_category is changed and it has a value', ->
    spyOn($scope, 'getCategoryArticles').and.returnValue true
    $scope.display_category = 'Test'
    $scope.$digest()
    expect($scope.getCategoryArticles).toHaveBeenCalled()

  it 'should call getCategoryArticle when display_category is changed and has no value', ->
    spyOn($scope, 'getCategoryArticles').and.returnValue true
    $scope.display_category = ''
    $scope.$digest()
    expect($scope.getCategoryArticles).not.toHaveBeenCalled()

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

  it 'should set the newLink model to a new Link', ->
    $scope.linkModalOpen = true
    $scope.$digest()
    $scope.linkModalOpen = false
    $scope.$digest()
    expect($scope.newLink).toEqual new Link()

  it 'should set the base variables when $scope.getCategoryArticles is called', ->
    $scope.selected_links = ['test']
    $scope.getCategoryArticles()
    expect($scope.selected_links).toEqual []
    expect($scope.loading_category).toEqual true

  it 'should call LinkResource.query when $scope.getCategoryArticles is called', ->
    spyOn(mockLinkResource, 'query').and.callThrough()
    $scope.getCategoryArticles()
    expect(mockLinkResource.query).toHaveBeenCalled()

  it 'should set $scope.selected_links to the returned value', ->
    $scope.getCategoryArticles()
    mockLinkQuery.resolve angular.copy(mockLinkQueryResponse)
    $scope.$digest()
    expect($scope.selected_links).toEqual mockLinkQueryResponse.links

  it 'should set $scope.loading_category to false when LinkResource.query succeeds', ->
    $scope.getCategoryArticles()
    mockLinkQuery.resolve angular.copy(mockLinkQueryResponse)
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.loading_category to false when LinkResource.query fails', ->
    $scope.getCategoryArticles()
    mockLinkQuery.reject()
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set emit an error when LinkResource.query fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.getCategoryArticles()
    mockLinkQuery.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not load links for category'

  it 'should set the appropriate variables when search_articles is called', ->
    $scope.link_results = ['test']
    $scope.search_articles()
    expect($scope.link_results).toEqual []
    expect($scope.searching).toEqual true

  it 'should set $scope.link_results to the returned values when LinkResource.query succeeds', ->
    $scope.search_articles()
    mockLinkQuery.resolve angular.copy(mockLinkQueryResponse)
    $scope.$digest()
    expect($scope.link_results).toEqual mockLinkQueryResponse.links

  it 'should set $scope.searching to false when LinkResource.query succeeds', ->
    $scope.search_articles()
    mockLinkQuery.resolve angular.copy(mockLinkQueryResponse)
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.searching to false when LinkResource.query succeeds', ->
    $scope.search_articles()
    mockLinkQuery.reject()
    $scope.$digest()
    expect($scope.loading_category).toEqual false

  it 'should set $scope.$emit to be called when LinkResource.query succeeds', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.search_articles()
    mockLinkQuery.reject()
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

#    TODO: test loaddashboard and down the controller