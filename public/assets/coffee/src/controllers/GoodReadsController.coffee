angular.module('astroApp').controller 'GoodReadsController', ['$scope', '$http', ($scope, $http)->
    $scope.page = 1
    $scope.loading = false

    $scope.$watch 'page', ->
      if !$scope.loading
        $scope.getGoodReadsBooks()

    $scope.getGoodReadsBooks = ->
      $scope.loading = true
      goodReadsPromise = $http.get '/api/book/goodreads?page=' + $scope.page

      goodReadsPromise.success (response)->
        $scope.books = response.books.titles
        $scope.pages = response.books.total / 20

      goodReadsPromise.error ->
        $scope.$emit 'errorOccurred', 'Could not load goodreads books'

      goodReadsPromise.finally ->
        $scope.loading = false
]