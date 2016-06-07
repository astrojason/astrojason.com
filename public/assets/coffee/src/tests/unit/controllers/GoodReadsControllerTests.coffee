describe 'GoodReadsController unit tests', ->
  $scope = null
  $httpBackend = null
  GoodReadsController = null
  mockGoodReadsQueryResponse = readJSON 'public/assets/coffee/src/tests/data/goodreads.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$httpBackend_)->
      $scope = $rootScope.$new()
      $httpBackend = _$httpBackend_

      mockInjections =
        $scope: $scope

      GoodReadsController = $controller 'GoodReadsController', mockInjections

  it 'should set the default values for the page variables', ->
    expect($scope.page).toEqual 1
    expect($scope.loading).toEqual false

  it 'should not call $scope.getGoodReadsBooks when the page changes and $scope.loading is true', ->
    spyOn($scope, 'getGoodReadsBooks').and.callThrough()
    $scope.loading = true
    $scope.$digest()
    $scope.page = 30
    $scope.$digest()
    expect($scope.getGoodReadsBooks).not.toHaveBeenCalled()

  it 'should call $scope.getGoodReadsBooks when the page changes and $scope.loading is false', ->
    $httpBackend.expectGET('/api/book/goodreads?page=30').respond angular.copy(mockGoodReadsQueryResponse)
    spyOn($scope, 'getGoodReadsBooks').and.callThrough()
    $scope.page = 30
    $scope.$digest()
    expect($scope.getGoodReadsBooks).toHaveBeenCalled()

  it 'should set $scope.loading to true when $scope.getGoodReadsBooks is called', ->
    $scope.getGoodReadsBooks()
    expect($scope.loading).toEqual true

  it 'should set $scope.loading to false when the goodreads api call succeeds', ->
    $httpBackend.expectGET('/api/book/goodreads?page=1').respond angular.copy(mockGoodReadsQueryResponse)
    $scope.getGoodReadsBooks()
    $httpBackend.flush()
    expect($scope.loading).toEqual false

  it 'should set $scope.books to the response items when the goodreads api call succeeds', ->
    $httpBackend.expectGET('/api/book/goodreads?page=1').respond angular.copy(mockGoodReadsQueryResponse)
    $scope.getGoodReadsBooks()
    $httpBackend.flush()
    expect($scope.books).toEqual mockGoodReadsQueryResponse.books.titles

  it 'should set $scope.loading to false when the goodreads api call fails', ->
    $httpBackend.expectGET('/api/book/goodreads?page=1').respond 500
    $scope.getGoodReadsBooks()
    $httpBackend.flush()
    expect($scope.loading).toEqual false

  it 'should call $scope.$emit when the goodreads api call fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $httpBackend.expectGET('/api/book/goodreads?page=1').respond 500
    $scope.getGoodReadsBooks()
    $httpBackend.flush()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not load goodreads books'