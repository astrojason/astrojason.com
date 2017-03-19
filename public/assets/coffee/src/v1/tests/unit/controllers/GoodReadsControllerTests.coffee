describe 'GoodReadsController unit tests', ->
  $scope = null
  GoodReadsController = null
  mockGoodReadsDeferred = null
  mockGoodReadsQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/goodreads.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockBookResource =
        goodreads: ->
          mockGoodReadsDeferred = $q.defer()
          $promise: mockGoodReadsDeferred.promise

      mockInjections =
        $scope: $scope
        BookResource: mockBookResource

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

  it 'should set $scope.loading to true when $scope.getGoodReadsBooks is called', ->
    $scope.getGoodReadsBooks()
    expect($scope.loading).toEqual true

  it 'should set $scope.loading to false when the goodreads api call succeeds', ->
    $scope.getGoodReadsBooks()
    mockGoodReadsDeferred.resolve angular.copy(mockGoodReadsQueryResponse)
    $scope.$digest()
    expect($scope.loading).toEqual false

  it 'should set $scope.books to the response items when the goodreads api call succeeds', ->
    $scope.getGoodReadsBooks()
    mockGoodReadsDeferred.resolve angular.copy(mockGoodReadsQueryResponse)
    $scope.$digest()
    expect($scope.books).toEqual mockGoodReadsQueryResponse.books.titles

  it 'should set $scope.loading to false when the goodreads api call fails', ->
    $scope.getGoodReadsBooks()
    mockGoodReadsDeferred.reject()
    $scope.$digest()
    expect($scope.loading).toEqual false

  it 'should call $scope.$emit when the goodreads api call fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.getGoodReadsBooks()
    mockGoodReadsDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not load goodreads books'