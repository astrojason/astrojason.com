window.app.controller 'BookController', ['$scope', '$http', '$timeout', '$controller', ($scope, $http, $timeout, $controller)->

  $controller 'FormMasterController', $scope: $scope

  $scope.setCategories = (categories)->
    $scope.categories = categories
    if categories?.length > 0
      $scope.recommendation_category = categories[0]

  $scope.getRecommendation = ->
    $scope.getting_recomendation = true
    reco_Promise = $http.get '/api/books/recommendation/' + $scope.recommendation_category
    reco_Promise.success (response)->
      $scope.book = response.book
    reco_Promise.finally ->
      $scope.getting_recomendation = false

  $scope.markAsRead = ->
    read_Promise = $http.post '/api/books/read/' + $scope.book.id
    read_Promise.success (response)->
      if response.success
        $scope.book.is_read = true
        # TODO: Call the parent markasread

  $scope.markAsUnread = ->
    read_Promise = $http.post '/api/books/unread/' + $scope.book.id
    read_Promise.success (response)->
      if response.success
        $scope.book.is_read = true

  $scope.delete = ->
    read_Promise = $http.post '/api/books/delete/' + $scope.book.id
    read_Promise.success (response)->
      if response.success
        if $scope.$parent.deleteItem
          $scope.$parent.deleteItem $scope.book

  $scope.save = ->
    data = $scope.book
    if $scope.book.category == 'New'
      $scope.book.category = $scope.new_category
    book_Promise = $http.post '/api/books/save', $.param data
    book_Promise.success (response)->
      if response.success
        if $scope.book_form.category.$dirty
          if $scope.$parent.changeBookCategory
            $scope.$parent.changeBookCategory $scope.book
        if $scope.book.category == $scope.new_category
          $scope.categories.push $scope.new_category
        $scope.editing = false
        if $scope.$parent.bookAdded
          $scope.$parent.bookAdded()
        alertify.success "Book " + (if 0 == parseInt $scope.book.id then "added" else "updated") + " successfully"
      else
        $scope.errorMessage = response.error
        if $scope.$parent.saveError
          $scope.$parent.saveError response.error
    book_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem ' + ($scope.book.id ? 'updating' : 'adding') + ' link'

  $scope.$watch 'search_query', (newValue, oldValue)->
    $scope.searching = true
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_books()
      , 500

  $scope.$watch 'is_read', ->
    if $scope.search_query?.length >= 3
      $scope.search_books()

  $scope.search_books = ->
    data =
      q: $scope.search_query
      include_read: $scope.is_read
    search_promise = $http.post '/api/books/search', $.param data
    search_promise.success (response)->
      $scope.search_results = response.books
]
