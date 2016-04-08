angular.module('astroApp').controller 'BookController', ['$scope', '$controller', '$timeout', '$filter', '$location',
  'BookResource', 'Book', 'AlertifyService', ($scope, $controller, $timeout, $filter, $location, BookResource, Book,
  AlertifyService)->

    $controller 'FormMasterController', $scope: $scope

    $scope.loading_books = false

    if !$scope.book?.id? && !$scope.recommendation
      $scope.editing = true

    $scope.$on 'bookDeleted', (event, message)->
      $scope.books = $filter('filter')($scope.books, {id: '!' + message})
      $scope.book_results = $filter('filter')($scope.book_results, {id: '!' + message})

    $scope.triggerRecommender = ->
      $scope.$watch 'recommendingBook', (newValue)->
        if newValue
          $scope.getRecommendation()

    $scope.initList = ->

      $scope.newBook = new Book()

      $scope.$on 'closeModal', (event, book)->
        $scope.bookModalOpen = false
        if book
          book.new = true
          $scope.books.splice(0, 0, book)
          $scope.newBook = new Book()
          $timeout ->
            book.new = false
          , 1000

      $scope.$watch 'filter_category', ->
        if !$scope.loading_books
          $scope.query()

      $scope.$watch 'book_query', ->
        if !$scope.loading_books
          $scope.searching = true
          $timeout.cancel $scope.search_timeout
          $scope.search_timeout = $timeout ->
            $scope.query()
          , 500

      $scope.$watch 'is_read', ->
        if !$scope.loading_books
          $scope.query()

      $scope.$watch 'sort', ->
        if !$scope.loading_books
          $scope.query()

      $scope.$watch 'descending', ->
        if !$scope.loading_books
          $scope.query()

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_books
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query()

    $scope.query = ->
      $scope.loading_books = true
      data =
        limit: $scope.limit
        page: $scope.page
      if $scope.book_query
        data['q'] = $scope.book_query
      if $scope.is_read
        data['include_read'] = $scope.is_read
      if $scope.filter_category
        data['category'] = $scope.filter_category
      if $scope.sort
        data['sort'] = $scope.sort
      if $scope.descending
        data['descending'] = true

      bookPromise = BookResource.query(data).$promise

      bookPromise.then (response)->
        $scope.books = response.books
        $scope.total = response.total
        $scope.pages = response.pages
        $scope.generatePages()

      bookPromise.finally ->
        $scope.loading_books = false

    $scope.save = ->
      if $scope.book.category == 'New'
        $scope.book.category = $scope.new_category

      book_promise = BookResource.save($scope.book).$promise

      book_promise.then (response)->
        AlertifyService.success "Book " + (if $scope.book.id then "updated" else "added") + " successfully"
        if $scope.book.id
          $scope.editing = false
        else
          $scope.$emit 'closeModal', response.book

      book_promise.catch (response)->
        $scope.errorMessage = response.data.error

    $scope.toggleRead = ->
      $scope.book.is_read = !$scope.book.is_read
      $scope.save()

    $scope.delete = ->
      success = ->
        AlertifyService.success 'Book deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'bookDeleted', $scope.book.id

      error = (response)->
        $scope.errorMessage = response.data.error

      song_promise = BookResource.remove id: $scope.book.id
      song_promise.$promise.then success, error

    $scope.getRecommendation = ->
      if $scope.recommendation_category
        success = (response)->
          $scope.book = response.book
        error = ->
          $scope.$emit 'errorOccurred', 'Could not get book recommendation'
        book_promise = BookResource.recommend category: $scope.recommendation_category
        book_promise.$promise.then success, error

    $scope.setCategories = (categories)->
      $scope.categories = categories
      if categories?.length > 0
        $scope.recommendation_category = categories[0]

    $scope.checkEditing = ->
      $scope.book?.id?
]
