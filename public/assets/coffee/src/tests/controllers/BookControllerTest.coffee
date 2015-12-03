describe 'BookController tests', ->
  $scope = null
  BookController = null
  mockBookResource = null
  mockAlertifyService = null
  mockBookQueryDeferred = null
  mockBookSaveDeferred = null
  mockBookRemoveDeferred = null
  mockBookRecommendDeferred = null
  mockBookQueryResponse = readJSON 'public/assets/coffee/src/tests/data/books.json'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockBookResource =
        query: ->
          mockBookQueryDeferred = $q.defer()
          $promise: mockBookQueryDeferred.promise

        save: ->
          mockBookSaveDeferred = $q.defer()
          $promise: mockBookSaveDeferred.promise

        remove: ->
          mockBookRemoveDeferred = $q.defer()
          $promise: mockBookRemoveDeferred.promise

        recommend: ->
          mockBookRecommendDeferred = $q.defer()
          $promise: mockBookRecommendDeferred.promise

      mockAlertifyService =
        success: ->
        error: ->

      mockInjections =
        $scope: $scope
        BookResource: mockBookResource
        AlertifyService: mockAlertifyService

      BookController = $controller 'BookController', mockInjections

  it 'should set the default variables', ->
    expect($scope.loading_books).toEqual false

  it 'should not generate a book recommendation when recommendingBook is set to true when the recommender has not been turned on', ->
    spyOn($scope, 'getRecommendation').and.callThrough()
    $scope.recommendingBook = true
    $scope.$digest()
    expect($scope.getRecommendation).not.toHaveBeenCalled()

  it 'should generate the book recommendation when recommendingBook is set to true when the recommender has been turned on', ->
    $scope.triggerRecommender()
    spyOn($scope, 'getRecommendation').and.callThrough()
    $scope.recommendingBook = true
    $scope.$digest()
    expect($scope.getRecommendation).toHaveBeenCalled()

  it 'should not try to update the list when filter_category changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.filter_category = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should try to update the list when filter_category changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.filter_category = 'test'
    $scope.$digest()
    expect($scope.get).toHaveBeenCalled()

  it 'should not try to update the list when filter_category changes if initList has not been called and loading_books is true', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.loading_books = true
    $scope.filter_category = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should not try to update the list when book_query changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.book_query = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should try to update the list when book_query changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.book_query = 'test'
    $scope.$digest()
    expect($scope.get).toHaveBeenCalled()

  it 'should not try to update the list when book_query changes if initList has not been called and loading_books is true', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.loading_books = true
    $scope.book_query = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should not try to update the list when is_read changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.is_read = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should try to update the list when is_read changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.is_read = 'test'
    $scope.$digest()
    expect($scope.get).toHaveBeenCalled()

  it 'should not try to update the list when is_read changes if initList has not been called and loading_books is true', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.loading_books = true
    $scope.is_read = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should not try to update the list when sort changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.sort = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should try to update the list when sort changes if initList has not been called', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.sort = 'test'
    $scope.$digest()
    expect($scope.get).toHaveBeenCalled()

  it 'should not try to update the list when sort changes if initList has not been called and loading_books is true', ->
    spyOn($scope, 'get').and.callThrough()
    $scope.initList()
    $scope.loading_books = true
    $scope.sort = 'test'
    $scope.$digest()
    expect($scope.get).not.toHaveBeenCalled()

  it 'should set loading_books to true when $scope.get is called', ->
    $scope.get()
    expect($scope.loading_books).toEqual true

  it 'should set loading_books to false when BookResource.query succeeds', ->
    $scope.get()
    mockBookQueryDeferred.resolve mockBookQueryResponse
    $scope.$digest()
    expect($scope.loading_books).toEqual false

  it 'should set loading_books to false when BookResource.query fails', ->
    $scope.get()
    mockBookQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_books).toEqual false

  it 'should set the appropriate variables when $scope.get resolves', ->
    $scope.get()
    mockBookQueryDeferred.resolve angular.copy(mockBookQueryResponse)
    $scope.$digest()
    expect($scope.books).toEqual mockBookQueryResponse.books
    expect($scope.total).toEqual mockBookQueryResponse.total
    expect($scope.pages).toEqual mockBookQueryResponse.pages

  it 'should call $scope.generatePages whe $scope.get resolves', ->
    spyOn($scope, 'generatePages').and.callThrough()
    $scope.get()
    mockBookQueryDeferred.resolve angular.copy(mockBookQueryResponse)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should call BookService.save when the save function is called', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    spyOn(mockBookResource, 'save').and.callThrough()
    $scope.save()
    expect(mockBookResource.save).toHaveBeenCalled()

  it 'should set $scope.editing to false on save success', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.editing = true
    $scope.save()
    mockBookSaveDeferred.resolve book: angular.copy($scope.book)
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should emit closeModal command when the save succeeds and this is a new book', ->
    spyOn($scope, '$emit')
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.book.id = null
    $scope.save()
    mockBookSaveDeferred.resolve book: angular.copy($scope.book)
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal', $scope.book

  it 'should call AlertifyService.success when the save succeeds', ->
    spyOn(mockAlertifyService, 'success').and.callThrough()
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.save()
    mockBookSaveDeferred.resolve book: angular.copy($scope.book)
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalled()

  it 'should set the errorMessage to the passed error message when save fails', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.save()
    mockBookSaveDeferred.reject data: error: 'This is an error'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is an error'

  it 'should change the read status', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.toggleRead()
    expect($scope.book.is_read).toEqual !mockBookQueryResponse.books[0].is_read

  it 'should call BookService.remove when delete is called', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    spyOn(mockBookResource, 'remove').and.callThrough()
    $scope.delete()
    expect(mockBookResource.remove).toHaveBeenCalled()

  it 'should set variables correctly on delete success', ->
    $scope.deleting = true
    $scope.editing = true
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.delete()
    mockBookRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.deleting).toEqual false
    expect($scope.editing).toEqual false

  it 'should call AlertifyService.success on delete success', ->
    spyOn(mockAlertifyService, 'success').and.callThrough()
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.delete()
    mockBookRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalled()

  it 'should set $scope.errorMessage to the passed error message on delete failure', ->
    $scope.book = angular.copy mockBookQueryResponse.books[0]
    $scope.delete()
    mockBookRemoveDeferred.reject data: error: 'This is a test message'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a test message'

  it 'should not call BookResource.recommend when getRecommendation is called and the category is not set', ->
    spyOn(mockBookResource, 'recommend').and.callThrough()
    $scope.getRecommendation()
    expect(mockBookResource.recommend).not.toHaveBeenCalled()

  it 'should call BookResource.recommend when getRecommendation is called and the category is set', ->
    $scope.recommendation_category = 'To Read'
    spyOn(mockBookResource, 'recommend').and.callThrough()
    $scope.getRecommendation()
    expect(mockBookResource.recommend).toHaveBeenCalled()

  it 'should set $scope.book to the recommended book when the recommendation succeeds', ->
    $scope.recommendation_category = 'To Read'
    $scope.getRecommendation()
    mockBookRecommendDeferred.resolve book: angular.copy(mockBookQueryResponse.books[0])
    $scope.$digest()
    expect($scope.book).toEqual mockBookQueryResponse.books[0]

  it 'should log an error the console when the recommendation fails', ->
    $scope.recommendation_category = 'To Read'
    spyOn(console, 'log').and.callThrough()
    $scope.getRecommendation()
    mockBookRecommendDeferred.reject()
    $scope.$digest()
    expect(console.log).toHaveBeenCalledWith 'Something went wrong'

  it 'should set the categories to the passed values', ->
    $scope.setCategories([1,2,3,4,5])
    expect($scope.categories).toEqual [1,2,3,4,5]
    expect($scope.recommendation_category).toEqual 1

  it 'should return false when there is no book id', ->
    expect($scope.checkEditing()).toEqual false

  it 'should return true when there is a book id', ->
    $scope.book = mockBookQueryResponse.books[0]
    expect($scope.checkEditing()).toEqual true