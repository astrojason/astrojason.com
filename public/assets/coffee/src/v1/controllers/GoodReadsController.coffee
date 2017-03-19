angular.module('astroApp').controller 'GoodReadsController', [
  '$scope'
  'BookResource'
  ($scope, BookResource)->
    $scope.page = 1
    $scope.loading = false

    $scope.getGoodReadsBooks = ->
      $scope.loading = true
      data =
        page: $scope.page

      goodReadsPromise = BookResource.goodreads(data).$promise

      goodReadsPromise.then (response)->
        $scope.books = response.books.titles
        $scope.pages = response.books.total / 20

      goodReadsPromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not load goodreads books'

      goodReadsPromise.finally ->
        $scope.loading = false
]