window.app.controller 'BookController', ['$scope', '$controller', '$timeout', 'Book', ($scope, $controller, $timeout, Book)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$watch 'search_query', (newValue)->
    $scope.searching = true
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_books()
      , 500

  $scope.$watch 'is_read', ->
    if $scope.search_query?.length >= 3
      $scope.search_books()

  $scope.$watch 'recommendingBook', (newValue)->
    if newValue
      $scope.getRecommendation()

  $scope.all = ->
    Book.query (response)->
      $scope.books = response.books

  $scope.search_books = ->
    $scope.searching = true
    data =
      q: $scope.search_query
      include_read: $scope.is_read
    Book.query data, (response)->
      $scope.search_results = response.books
      $scope.searching = false

  $scope.save = ->
    if $scope.book.category == 'New'
      $scope.book.category = $scope.new_category

    success = ->
      alertify.success "Book " + (if $scope.book.id then "updated" else "added") + " successfully"
      if $scope.book.id
        $scope.editing = false
      else
        $scope.$emit 'closeModal'

    error = (response)->
      $scope.errorMessage = response.data.error

    book_promise = Book.save $.param $scope.book
    book_promise.$promise.then success, error

  $scope.toggleRead = ->
    $scope.book.is_read = !$scope.book.is_read
    $scope.save()

  $scope.delete = ->
    success = ->
      alertify.success 'Book deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      if $scope.$parent.removeBook
        $scope.$parent.removeBook $scope.index

    error = (response)->
      $scope.errorMessage = response.data.error

    song_promise = Book.remove id: $scope.book.id
    song_promise.$promise.then success, error

  $scope.removeBook = (index)->
    $scope.books.splice index, 1

  $scope.getRecommendation = ->
    if $scope.recommendation_category
      success = (response)->
        $scope.book = response.book
      error = ->
        console.log 'Something went wrong'
      book_promise = Book.recommend category: $scope.recommendation_category
      book_promise.$promise.then success, error

  $scope.setCategories = (categories)->
    $scope.categories = categories
    if categories?.length > 0
      $scope.recommendation_category = categories[0]

  $scope.checkEditing = ->
    return $scope.book?.id
]
