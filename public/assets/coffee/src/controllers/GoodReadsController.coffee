angular.module('astroApp').controller 'GoodReadController', ['$scope', '$http', ($scope, $http)->

  $scope.getGoodReadsBooks = ->
    data =
      page: 1
    goodReadsPromise = $http.get '/api/books/goodreads', data

    goodReadsPromise.success (response)->
      console.log response
]