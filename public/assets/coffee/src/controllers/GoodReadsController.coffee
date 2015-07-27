angular.module('astroApp').controller 'GoodReadsController', ['$scope', '$http', ($scope, $http)->

  $scope.getGoodReadsBooks = ->
    data =
      page: 1
    goodReadsPromise = $http.get '/api/book/goodreads', data

    goodReadsPromise.success (response)->
      console.log response
]